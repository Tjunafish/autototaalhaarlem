<?php

namespace Autoplan\ServiceBundle\Search;

class Sphinxsearch
{
	/**
	 * @var string $host
	 */
	private $host;

	/**
	 * @var string $port
	 */
	private $port;

	/**
	 * @var string $socket
	 */
	private $socket;

	/**
	 * @var array $indexes
	 *
	 * $this->indexes should have the format:
	 *
	 *	$this->indexes = array(
	 *		'IndexLabel' => array(
	 *			'index_name'	=> 'IndexName',
	 *			'field_weights'	=> array(
	 *				'FieldName'	=> (int)'FieldWeight',
	 *				...,
	 *			),
	 *		),
	 *		...,
	 *	);
	 */
	private $indexes;

	/**
	 * @var \SphinxClient $sphinx
	 */
	private $sphinx;

	/**
     * Constructor.
     *
	 * @param string $host The server's host name/IP.
	 * @param string $port The port that the server is listening on.
	 * @param string $socket The UNIX socket that the server is listening on.
	 * @param array $indexes The list of indexes that can be used.
	 */
	public function __construct($host = 'localhost', $port = '9312', $socket = null)
	{
//var_dump ($host);
//die();
        $this->indexes = array(
	        'Autoplan' => array(
	            'index_name'	=> 'autoplan',
	            'field_weights'	=> array(
	                'title'	        => (int)50,
                    'introduction'	=> (int)10,
                    'content'	    => (int)1,
	            ),
            ),
        );
		$this->host = $host;
		$this->port = $port;
		$this->socket = $socket;

		$this->sphinx = new \SphinxClient();
		if( $this->socket !== null )
			$this->sphinx->setServer($this->socket);
		else
			$this->sphinx->setServer($this->host, $this->port);
	}

    public function initSphinx() {
        $this->sphinx->SetSortMode(SPH_SORT_RELEVANCE);
        $this->sphinx->SetSelect("*");
        $this->sphinx->ResetFilters();
        $this->sphinx->ResetGroupBy();
        $this->sphinx->ResetOverrides();
        $this->sphinx->SetLimits(0,10);
    }

	/**
     * Set the desired match mode.
     *
	 * @param int $mode The matching mode to be used.
	 */
	public function setMatchMode($mode)
	{
		$this->sphinx->setMatchMode($mode);
	}

    public function setSelect($sSelect) {
        $this->sphinx->SetSelect($sSelect);
    }

    public function resetGroupBy() {
        $this->sphinx->ResetGroupBy();
    }

	/**
     * Set the desired search filter.
     *
	 * @param string $attribute The attribute to filter.
	 * @param array $values The values to filter.
	 * @param bool $exclude Is this an exclusion filter?
	 */
	public function setFilter($attribute, $values, $exclude = false)
	{
		$this->sphinx->setFilter($attribute, $values, $exclude);
	}

    public function setFilterRange($attribute, $min, $max, $exclude = false)
    {
        $this->sphinx->SetFilterRange($attribute, $min, $max, $exclude);
    }

    public function setFilterFloatRange($attribute, $min, $max, $exclude = false)
    {
        $this->sphinx->SetFilterFloatRange($attribute, $min, $max, $exclude);
    }

    /**
     * Set the desired sorting order
     *
     * @param int $iSortMode The sorting mode
     * @param string $attribute The attribute to use for sorting.
     * @param array $values The values to filter.
     * @param bool $exclude Is this an exclusion filter?
     */
    public function setSortBy($iSortMode, $attribute)
    {
        $this->sphinx->SetSortMode($iSortMode, $attribute);
    }

    /**
     * Set the desired sorting order
     *
     * @param string $attribute The attribute to use for sorting.
     * @param string $func
     * @param string $groupsort
     * @param bool $exclude Is this an exclusion filter?
     */
    public function setGroupBy($attribute, $func, $groupsort="@group desc")
    {
        $this->sphinx->SetGroupBy($attribute, $func, $groupsort);
    }

    public function setRankingMode($sMode) {
        $this->sphinx->SetRankingMode($sMode);
    }

    public function setSortMode($sMode, $sBy) {
        $this->sphinx->SetSortMode($sMode, $sBy);
    }

    public function setFieldWeights($aWeights) {
        $this->sphinx->SetFieldWeights($aWeights);
    }

    public function setLimit($iLimit, $iOffset) {
        $this->sphinx->SetLimits($iOffset, $iLimit);
    }

    public function setArrayResult() {
        $this->sphinx->SetArrayResult(true);
    }

    public function addQuery($sQuery, $sIndex) {
        $this->sphinx->AddQuery($sQuery, $sIndex);
    }

    public function escapeString($sStr) {
        return $this->sphinx->EscapeString($sStr);
    }

    public function runQueries() {
        return $this->sphinx->RunQueries();
    }

	/**
     * Search for the specified query string.
     *
	 * @param string $query The query string that we are searching for.
	 * @param array $indexes The indexes to perform the search on.
	 *
	 * @return array The results of the search.
	 *
	 * $indexes should have the format:
	 *
	 *	$indexes = array(
	 *		'IndexLabel' => array(
	 *			'result_offset'	=> (int),
	 *			'result_limit'	=> (int)
	 *		),
	 *		...,
	 *	);
	 */
	public function search($query, array $indexes)
	{
		//$query = $this->sphinx->escapeString($query);

        $aSearchIndexes = array();

		$results = array();
		foreach( $indexes as $label ) {
			/**
			 * Ensure that the label corresponds to a defined index.
			 */
			if( !isset($this->indexes[$label]) )
				continue;

			/**
			 * Weight the individual fields.
			 */
			if( !empty($this->indexes[$label]['field_weights']) )
				$this->sphinx->setFieldWeights($this->indexes[$label]['field_weights']);

            $aSearchIndexes[] = $this->indexes[$label]['index_name'];

		}

        /**
         * Perform the query.
         */
        $results = $this->sphinx->query($query, implode(';',$aSearchIndexes));
 //        if( $results['status'] !== SEARCHD_OK ) {
 //        	throw new \RuntimeException(sprintf('Searching index "%s" for "%s" failed with error "%s" or warning "%s".', $label, $query, $this->sphinx->_error, $this->sphinx->_warning));
 //        }

		/**
		 * FIXME: Throw an exception if $results is empty?
		 */
// var_dump($results);
		return $results;
	}
}

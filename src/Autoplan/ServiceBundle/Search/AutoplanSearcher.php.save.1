<?php
/**
 * Created by JetBrains PhpStorm.
 * User: simonsabelis
 * Date: 9/17/12
 * Time: 10:26
 * To change this template use File | Settings | File Templates.
 */

namespace Autoplan\ServiceBundle\Search;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAware;


use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;

class AutoplanSearcher extends ContainerAware
{

    const SECTION_AUTOPLAN = 0;
    const SECTION_ASH = 1;

    private $iSection = 0;
    const MAX = 1000;

    private $fPropertyRatio = 1.3;

    /**
     * 1: news
     * 2: artist
     * 3: title
     * 4: chart (week)
     */

    /** @var EntityManager $oDoctrine */
    public $oEm;

    /** @var Sphinxsearch $oSearcher */
    public $oSearcher;

    private $searchQuery = '';

    public $aDefaultFilters = array();


    public function __construct(Container $oContainer) {
        $this->setContainer($oContainer);
        $this->oSearcher = $this->container->get('autoplan.sphinxsearch.search.api');
// echo 'dump in AutoplanSearcher.php';
// echo '<pre />'; var_dump($this->oSearcher);        
$this->aDefaultFilters = array(
            'price_min' => 0,
            'price_max' => 0,
            'brand' => 0,
            'model' => 0,
        );
// $this->getBrands();
    }

    public function setSection($iSection) {
        $this->iSection = $iSection;
    }


    /** brands searcher */
    //public function getAssociationCount($aProvince = array()) {

        /** @var Sphinxsearch $sphinxSearch */
        /*$this->oSearcher->initSphinx();
        if(is_array($aProvince) && sizeof($aProvince) > 0)
            $this->oSearcher->setFilter("province_id",$aProvince);
        $this->oSearcher->setGroupBy("association_id",SPH_GROUPBY_ATTR,"association_id_title asc");
        $this->oSearcher->setSortBy(SPH_SORT_ATTR_ASC, "association_id_title");
        $this->oSearcher->setLimit($this->iMax, 0);
        $aResults = $this->oSearcher->search('', array('VJP'));

        return $aResults['total'];
    }*/

    public function getBrands() {

        /** @var Sphinxsearch $sphinxSearch */
        $this->oSearcher->initSphinx();
        $this->oSearcher->setLimit(self::MAX,0);
        $this->oSearcher->setFilter("section",array(self::SECTION_ASH));

        $this->oSearcher->setGroupBy("brand_id",SPH_GROUPBY_ATTR,"brand_id_title asc");
        $this->oSearcher->setSortBy(SPH_SORT_ATTR_ASC, "brand_id_title");
        $aResults = $this->oSearcher->search('', array('Autoplan'));
// var_dump($aResults);
        $aBrands = array();
        if($aResults['total'] > 0) {
            foreach($aResults['matches'] as $aResult) {
                $aBrands[$aResult["attrs"]["brand_id_slug"]] = array(
                    'title' =>$aResult['attrs']['brand_id_title'],
                    'id' =>$aResult['attrs']['brand_id'],
                );
            }
        }

        return $aBrands;
    }

    public function getModels() {

        /** @var Sphinxsearch $sphinxSearch */
        $this->oSearcher->initSphinx();
        $this->oSearcher->setLimit(self::MAX,0);
        $this->oSearcher->setFilter("section",array(self::SECTION_ASH));

        $this->oSearcher->setGroupBy("model_id",SPH_GROUPBY_ATTR,"model_id_title asc");
        $this->oSearcher->setSortBy(SPH_SORT_ATTR_ASC, "model_id_title");
        $aResults = $this->oSearcher->search('', array('Autoplan'));

        $aModels = array();
        if($aResults['total'] > 0) {
            foreach($aResults['matches'] as $aResult) {
                if(!isset($aModels[$aResult["attrs"]["brand_id"]])) {
                    $aModels[$aResult["attrs"]["brand_id"]] = array();
                }
                $aModels[$aResult["attrs"]["brand_id"]][] = array(
                    'title' =>$aResult['attrs']['model_id_title'],
                    'id' =>$aResult['attrs']['model_id'],
                    'brand_id' =>$aResult['attrs']['brand_id'],
                );
            }
        }

        return $aModels;
    }

    public function getFuels() {

        /** @var Sphinxsearch $sphinxSearch */
        $this->oSearcher->initSphinx();
        $this->oSearcher->setLimit(self::MAX,0);
        $this->oSearcher->setFilter("section",array(self::SECTION_ASH));

        $this->oSearcher->setGroupBy("fuel_id",SPH_GROUPBY_ATTR,"fuel_id_title asc");
        $this->oSearcher->setSortBy(SPH_SORT_ATTR_ASC, "fuel_id_title");
        $aResults = $this->oSearcher->search('', array('Autoplan'));

        $aFuels = array();
        if($aResults['total'] > 0) {
            foreach($aResults['matches'] as $aResult) {
                $aFuels[] = array(
                    'title' =>$aResult['attrs']['fuel_id_title'],
                    'id' =>$aResult['attrs']['fuel_id'],
                );
            }
        }

        return $aFuels;
    }

    public function getPopularCars($aPopular = array(), $iMax = 30) {
        if(sizeof($aPopular) == 0) {
            return array();
        }
        $this->searchQuery = '';

        /** @var Sphinxsearch $sphinxSearch */
        $this->oSearcher->initSphinx();
        $this->oSearcher->setSortMode(SPH_SORT_ATTR_DESC, "created");
        //$this->oSearcher->setLimit($iMax, 0);

        $aPopId = array();
        foreach($aPopular as $iId) {
            if(intval($iId) > 0) {
                $aPopId[] = $iId;
            }
        }

        $this->oSearcher->setFilter("object_id", $aPopId);

        $aResults = $this->oSearcher->search("", array('Autoplan'));

        if(intval($aResults['total']) > 0) {
            return $aResults['matches'];
        } else {
            return array();
        }
    }

    public function getCarCount($iSection = null) {
        $this->searchQuery = '';

        /** @var Sphinxsearch $sphinxSearch */
        $this->oSearcher->initSphinx();
        $this->oSearcher->setSortMode(SPH_SORT_ATTR_DESC, "created");
        $this->oSearcher->setLimit(self::MAX, 0);

        $this->oSearcher->setFilter("section",array(self::SECTION_ASH));

        $aResults = $this->oSearcher->search("", array('Autoplan'));

        return $aResults['total'];
    }


    public function getNewCars($iSection = null, $iMax = 30) {
        $this->searchQuery = '';

        /** @var Sphinxsearch $sphinxSearch */
        $this->oSearcher->initSphinx();
        $this->oSearcher->setSortMode(SPH_SORT_ATTR_DESC, "created");
        $this->oSearcher->setLimit($iMax, 0);

        $this->oSearcher->setFilter("section",array(self::SECTION_ASH));

        $aResults = $this->oSearcher->search("", array('Autoplan'));

        if(intval($aResults['total']) > 0) {
            return $aResults['matches'];
        } else {
            return array();
        }
    }

    public function getOfferCars($iSection = null) {
        $this->searchQuery = '';

        /** @var Sphinxsearch $sphinxSearch */
        $this->oSearcher->initSphinx();
        $this->oSearcher->setSortMode(SPH_SORT_ATTR_DESC, "created");
        $this->oSearcher->setLimit(self::MAX, 0);
        $this->oSearcher->setFilter('isoffer', array(1));

        $this->oSearcher->setFilter("section",array(self::SECTION_ASH));

        $aResults = $this->oSearcher->search("", array('Autoplan'));

        if(intval($aResults['total']) > 0) {
            return $aResults['matches'];
        } else {
            return array();
        }
    }

    public function getMaxForField($sField) {
        $aMax = array();

        $this->oSearcher->initSphinx();
        $this->oSearcher->setSortMode(SPH_SORT_ATTR_DESC, $sField);
        $this->oSearcher->setLimit(1, 0);


        $aResults = $this->oSearcher->search("", array('Autoplan'));
        if(isset($aResults['matches'])) {
            $aResult = array_pop($aResults['matches']);
            return $aResult['attrs'][$sField];
        }
        return 0;
    }

    public function getMinForField($sField) {
        $aMax = array();

        $this->oSearcher->initSphinx();
        $this->oSearcher->setSortMode(SPH_SORT_ATTR_ASC, $sField);
        $this->oSearcher->setLimit(1, 0);

        $this->oSearcher->setFilterRange($sField, 0, 10, true);


        $aResults = $this->oSearcher->search("", array('Autoplan'));
        if(isset($aResults['matches'])) {
            $aResult = array_pop($aResults['matches']);
            return $aResult['attrs'][$sField];
        }
        return 0;
    }


    public function carTotal($aFilters) {
        $this->searchQuery = '';

        /** @var Sphinxsearch $sphinxSearch */
        $this->oSearcher->initSphinx();

        $this->applyFilters($aFilters);

        if(isset($aFilters['searchBy']) && $aFilters['searchBy'] == 2) {
            $this->searchQuery = $aFilters['search_query'];
        }
        $this->oSearcher->setSortMode(SPH_SORT_ATTR_ASC, 'sortname');

        $aResults = $this->oSearcher->search($this->searchQuery, array('Autoplan'));

        return $aResults['total'];
    }

    public function carSearch($aFilters, $iSection = 0, $aSort = array(), $iMax, $iPage = 1) {
        $this->searchQuery = '';

        /** @var Sphinxsearch $sphinxSearch */
        $this->oSearcher->initSphinx();

        $this->oSearcher->setLimit($iMax, ($iPage-1)*$iMax);



        if(isset($aFilters['searchBy']) && $aFilters['searchBy'] == 2) {

            $this->applyFilters($aFilters, array('section'));
            $this->searchQuery = $aFilters['search_query'];
        } else {
            $this->applyFilters($aFilters);
        }

        $this->oSearcher->setSortMode(SPH_SORT_ATTR_ASC, 'sortname');

        //$oSearcher->addSphinxSorting($oUser->getSort());
        //$oSearcher->setSelectQuery();
        //$oSearcher->doSearch();

        $aResults = $this->oSearcher->search($this->searchQuery, array('Autoplan'));

        if($aResults['total'] > 0) {
            return $aResults['matches'];
        } else {
            return array();
        }
    }

    public function carSearchAlternatives($aFilters, $iSection = 0, $aSort = array(), $iMax, $iPage = 1) {
        $this->searchQuery = '';

        /** @var Sphinxsearch $sphinxSearch */
        $this->oSearcher->initSphinx();

        $this->oSearcher->setLimit($iMax, ($iPage-1)*$iMax);



        $this->oSearcher->setFilter("section",array(self::SECTION_ASH));

        if(isset($aFilters['searchBy']) && $aFilters['searchBy'] == 2) {

            $this->applyFilters($aFilters, array('section'));
            $this->searchQuery = $aFilters['search_query'];
        } else {
            $this->applyFilters($aFilters);
        }

        $this->oSearcher->setSortMode(SPH_SORT_ATTR_ASC, 'sortname');

        //$oSearcher->addSphinxSorting($oUser->getSort());
        //$oSearcher->setSelectQuery();
        //$oSearcher->doSearch();

        $aResults = $this->oSearcher->search($this->searchQuery, array('Autoplan'));

        if($aResults['total'] > 0) {
            return $aResults['matches'];
        } else {
            return array();
        }
    }


    /** @todo reengineer */




    /** search */
    /**
     */
    public function propertyTotal($aFilters, $iCategory, $sRefine, $sLocation = null, $aSubsiteFilter = array()) {

        $this->searchQuery = '';

        /** @var Sphinxsearch $sphinxSearch */
        $this->oSearcher->initSphinx();

        $this->oSearcher->setLimit($this->iMax, 0);

        if(null!==$iCategory) {
            $this->oSearcher->setFilter('category', array($iCategory));
        }

        foreach($aSubsiteFilter as $sFilter => $aVal) {
            $this->oSearcher->setFilter($sFilter, $aVal);
        }

        if($sRefine=='development') {
            $this->oSearcher->setFilter('development', array(1));
        } elseif($sRefine == 'openhouse') {
            $this->oSearcher->setFilter('publicvisit', array(1));
        }

        $aFilters = $this->fillEmptyValues($aFilters);
        $this->applyFilters($aFilters);
        if(strlen($sLocation) > 0) {
            $this->applyLocation($sLocation);
        }

        $aResults = $this->oSearcher->search($this->searchQuery, array('VJP'));
        if($aResults['total'] > 700) {
            return intval($aResults['total'] * $this->fPropertyRatio);
        }
        return $aResults['total'];
    }


    public function calculateMultipleFacet($aFilters, $iCategory, $sRefine, $sLocation, $sFacet, $iMax = 0) {
        $this->searchQuery = '';
        $this->oSearcher->initSphinx();

        $this->oSearcher->setFilter('category', array($iCategory));
        if($sRefine=='development') {
            $this->oSearcher->setFilter('development', array(1));
        } elseif($sRefine == 'openhouse') {
            $this->oSearcher->setFilter('publicvisit', array(1));
        }

        $aFilters = $this->fillEmptyValues($aFilters);
        $this->applyFilters($aFilters, array($sFacet));
        if(strlen($sLocation) > 0) {
            $this->applyLocation($sLocation);
        }


        $this->oSearcher->setGroupBy($sFacet,SPH_GROUPBY_ATTR);
        //$this->oSearcher->setSortBy(SPH_SORT_EXTENDED, "@count desc");
        $this->oSearcher->setLimit($this->iMax, 0);
        $this->oSearcher->setArrayResult();
        $this->oSearcher->setSelect("@groupby, ".$sFacet.", @count");
        $aResults = $this->oSearcher->search($this->searchQuery, array('VJP'));

        $iCount = 0;
        $aReturn = array();
        if($aResults['total'] > 0) {
            foreach($aResults['matches'] as $aResult) {

                $aReturn[] = array(
                    'id'    => $aResult['attrs']['@groupby'],
                    'count' => $aResult['attrs']['@count'],
                );

                $iCount++;

            }
        }

        return $aReturn;
    }

    public function calculateFacet($aFilters, $iCategory, $sRefine, $sLocation, $sFacet) {
        $this->searchQuery = '';
        $this->oSearcher->initSphinx();

        $this->oSearcher->setFilter('category', array($iCategory));
        if($sRefine=='development') {
            $this->oSearcher->setFilter('development', array(1));
        } elseif($sRefine == 'openhouse') {
            $this->oSearcher->setFilter('publicvisit', array(1));
        }

        $aFilters = $this->fillEmptyValues($aFilters);
        $this->applyFilters($aFilters, array($sFacet));
        if(strlen($sLocation) > 0) {
            $this->applyLocation($sLocation);
        }


        $this->oSearcher->setGroupBy($sFacet."_id",SPH_GROUPBY_ATTR,"@count desc");
        //$this->oSearcher->setSortBy(SPH_SORT_EXTENDED, "@count desc");
        $this->oSearcher->setLimit($this->iMax, 0);
        $aResults = $this->oSearcher->search($this->searchQuery, array('VJP'));

        $iCount = 0;
        $aReturn = array();
        if($aResults['total'] > 0) {
            foreach($aResults['matches'] as $aResult) {
                if(strlen(trim($aResult['attrs'][$sFacet.'_id_title']))===0) {
                    continue;
                }

                $aReturn[] = array(
                    'id'    => $aResult['attrs'][$sFacet.'_id'],
                    'title' => $aResult['attrs'][$sFacet.'_id_title'],
                    'count' => $aResult['attrs']['@count'],
                );

                $iCount++;

            }
        }

        return $aReturn;
    }


    /** footer search requests */

    public function getFooterResults($iSearchSection, $sKey, $iMaxResults = null, $aSubsiteFilter = array()) {
        /** @var Sphinxsearch $sphinxSearch */
        $this->oSearcher->initSphinx();


        $this->oSearcher->setFilter('category', array($iSearchSection));

        foreach($aSubsiteFilter as $sFilter => $aVal) {
            $this->oSearcher->setFilter($sFilter, $aVal);
        }

        $this->oSearcher->setGroupBy($sKey."_id",SPH_GROUPBY_ATTR,"@count desc");
        //$this->oSearcher->setSortBy(SPH_SORT_EXTENDED, "@count desc");
        $this->oSearcher->setLimit($this->iMax, 0);
        $aResults = $this->oSearcher->search('', array('VJP'));

        $iCount = 0;
        $aReturn = array();
        if($aResults['total'] > 0) {
            foreach($aResults['matches'] as $aResult) {

                if(null!==$iMaxResults && $iCount >= $iMaxResults) {
                    break;
                }
                if(strlen(trim($aResult['attrs'][$sKey.'_id_slug']))===0) {
                    continue;
                }

                if($sKey == 'city') {
                    $aReturn[] = array(
                        'title' => $aResult['attrs'][$sKey.'_id_title'],
                        'slug' => $aResult['attrs'][$sKey.'_id_slug'],
                        'province_title' => $aResult['attrs']['province_id_title'],
                        'province_slug' => $aResult['attrs']['province_id_slug'],
                        'count' => $aResult['attrs']['@count'],
                    );
                } else if($sKey == 'street'){
                    $aReturn[] = array(
                        'title' => $aResult['attrs'][$sKey.'_id_title'],
                        'slug' => $aResult['attrs'][$sKey.'_id_slug'],
                        'city_title' => $aResult['attrs']['city_id_title'],
                        'street_city' => urlencode($aResult['attrs'][$sKey.'_id_title'] . ", " . $aResult['attrs']['city_id_title']),
                        'count' => $aResult['attrs']['@count'],
                    );
                } else {
                    $aReturn[] = array(
                        'id' => $aResult['attrs'][$sKey.'_id'],
                        'title' => $aResult['attrs'][$sKey.'_id_title'],
                        'slug' => $aResult['attrs'][$sKey.'_id_slug'],
                        'count' => $aResult['attrs']['@count'],
                    );
                }

                $iCount++;

            }
        }

        return $aReturn;
    }

    public function randomProperties($iCategory, $aSubsiteFilter) {
        $this->searchQuery = '';

        /** @var Sphinxsearch $sphinxSearch */
        $this->oSearcher->initSphinx();

        $this->oSearcher->setFilter('category', array($iCategory));


        foreach($aSubsiteFilter as $sFilter => $aVal) {
            $this->oSearcher->setFilter($sFilter, $aVal);
        }

        $this->oSearcher->setSortMode(SPH_SORT_EXTENDED, "@random");
        $this->oSearcher->setLimit(10, 0);

        $aResults = $this->oSearcher->search("", array('VJP'));

        if(intval($aResults['total']) > 0) {
            return $aResults['matches'];
        } else {
            return array();
        }
    }

    public function filterProperties($aFilters, $iCategory, $sRefine, $sLocation, $aSort) {
        $this->searchQuery = '';

        /** @var Sphinxsearch $sphinxSearch */
        $this->oSearcher->initSphinx();

        $this->oSearcher->setLimit($this->iMax, 0);
        $this->oSearcher->setSortMode(SPH_SORT_ATTR_DESC, 'created');

        $this->oSearcher->setFilter('category', array($iCategory));

        if($sRefine=='development') {
            $this->oSearcher->setFilter('development', array(1));
        } elseif($sRefine == 'openhouse') {
            $this->oSearcher->setFilter('publicvisit', array(1));
        }

        $aFilters = $this->fillEmptyValues($aFilters);
        $this->applyFilters($aFilters);
        if(strlen($sLocation) > 0) {
            $this->applyLocation($sLocation);
        }

        $this->oSearcher->setSortMode(SPH_SORT_ATTR_DESC, 'created');

        if(is_array($aSort) && sizeof($aSort) == 2) {
            if($aSort[0] == 'date') {
                if($aSort[1] == 'asc') {
                    $this->oSearcher->setSortMode(SPH_SORT_ATTR_ASC, 'created');
                } else {
                    $this->oSearcher->setSortMode(SPH_SORT_ATTR_DESC, 'created');
                }
            } elseif($aSort[0] == 'price') {
                if($aSort[1] == 'asc') {
                    $this->oSearcher->setSortMode(SPH_SORT_ATTR_ASC, 'price');
                } else {
                    $this->oSearcher->setSortMode(SPH_SORT_ATTR_DESC, 'price');
                }

            } elseif($aSort[0] == 'size'){
                if($aSort[1] == 'asc') {
                    $this->oSearcher->setSortMode(SPH_SORT_ATTR_ASC, 'surface');
                } else {
                    $this->oSearcher->setSortMode(SPH_SORT_ATTR_DESC, 'surface');
                }

            }
        }

        $aResults = $this->oSearcher->search($this->searchQuery, array('VJP'));

        return $aResults;
    }

    /** general functions */
    private function applyFilters($aFilters, $aExclude = array()) {
        if(isset($aFilters['min_price'])) {
            if($aFilters['min_price'] > 0 && $aFilters['max_price'] > 0) {
                $this->oSearcher->setFilterFloatRange('price', floatval($aFilters['min_price']), floatval($aFilters['max_price']));
            } else if($aFilters['min_price'] > 0 ) {
                $this->oSearcher->setFilterFloatRange('price', 0.0, floatval($aFilters['min_price']), true);
            } else if($aFilters['max_price'] > 0 ) {
                $this->oSearcher->setFilterFloatRange('price', 0.0, floatval($aFilters['max_price']));
            }
        }


        if(isset($aFilters['brand_id']) && $aFilters['brand_id'] > 0) {
            $this->oSearcher->setFilter("brand_id", array($aFilters['brand_id']));
        }
        if(isset($aFilters['model_id']) && $aFilters['model_id'] > 0) {
            $this->oSearcher->setFilter("model_id", array($aFilters['model_id']));
        }

        if(isset($aFilters['fuel_id']) && $aFilters['fuel_id'] > 0) {
            $this->oSearcher->setFilter("fuel_id", array($aFilters['fuel_id']));
        }

        if(isset($aFilters['chassis_id']) && $aFilters['chassis_id'] > 0) {
            $this->oSearcher->setFilter("chassis_id", array($aFilters['chassis_id']));
        }

        if(isset($aFilters['kmcounter']) && $aFilters['kmcounter'] > 0) {
            $this->oSearcher->setFilterRange("kmcounter", 0, intval($aFilters['kmcounter']));
        }

        if(isset($aFilters['year']) && $aFilters['year'] > 0) {
            $this->oSearcher->setFilterRange("constructionyear", intval($aFilters['year']), date("Y"));
        }

        $this->oSearcher->setFilter("section",array(self::SECTION_ASH));
    }

    private function applyLocation($sLocation) {

        $this->oSearcher->setMatchMode(SPH_MATCH_EXTENDED);
        if(false !== stripos($sLocation,'provincie')) {

            // province search
            $sLocation = trim(str_ireplace('(Provincie) ', '', $sLocation));
            $this->searchQuery = "@province_id_title ( '".$sLocation."' )";

        } else if(false !== strpos($sLocation, ',')) {
            // street search
            $aLocation = explode(',', $sLocation);
            $this->searchQuery = "@street ( '".trim($aLocation[0])."' ) & @city_id_title ( ".$aLocation[1]." )";

        } else {
            // city search
            $this->searchQuery = "@city_id_title ( '".$sLocation."' )";

        }
    }

    /** old functions */

    /**
     * @param $iLocal int id of local to search content for
     * @param $aType array of content types to search for
     * @return array object array
     */
    public function doSearch($sQuery, $sType, $iMax = 10, $iPage = 0, $aFilters = array(), $sSortBy = "created", $sSort = "DESC") {

        /** @var Sphinxsearch $sphinxSearch */
        $this->oSearcher->initSphinx();
        $this->oSearcher->setMatchMode(SPH_MATCH_EXTENDED2);

        if($sSortBy !== null) {
            if($sSort == "DESC")
                $this->oSearcher->setSortBy(SPH_SORT_ATTR_DESC, $sSortBy);
            else
                $this->oSearcher->setSortBy(SPH_SORT_ATTR_ASC, $sSortBy);
        }

        $this->oSearcher->setLimit($iMax, $iPage*$iMax);

        if($sType == 'nochart') {
            $this->oSearcher->setFilter('type_id', array(4), true);
        } else {
            $this->oSearcher->setFilter('type_id', array(4), false);
        }

        foreach($aFilters as $sKey => $aVal) {
            $this->oSearcher->setFilter($sKey,$aVal);
        }

        $this->oSearcher->setRankingMode(SPH_RANK_SPH04);
        $this->oSearcher->setFieldWeights(array(
            'title' => 10,
            'introduction' => 2,
            'content' => 1
        ));



        $aSearchContent = $this->oSearcher->search($sQuery, array('Content'));

        $aResults = array('total' => $aSearchContent['total'], 'matches' => array());

        if($aSearchContent['total'] > 0 && isset($aSearchContent['matches']) ) {
            foreach($aSearchContent['matches'] as $iId => $aResultArray) {

                $aContentArray = $aResultArray['attrs'];
                $aContentArray['id']= $aContentArray['object_id'];

                $aResults['matches'][] = $aContentArray;
            }
        }

        return $aResults;
    }

    /**
     * @static

     */
    public  function getTotal($aFilters = array()) {

        $this->oSearcher->initSphinx();
        $this->oSearcher->setMatchMode(SPH_MATCH_EXTENDED2);

        //$oSearcher->setPage(1);

        /*$oSearcher->buildSphinxFilters($oUser->getFilters());
        $oSearcher->addPlaceFilter($oUser->getFilters(), $oUser->getEnabledPlaces());
        //$oSearcher->addSphinxSorting($oUser->getSort());
        $oSearcher->setSelectQuery();
        $oSearcher->doSearch();*/

        //return $oSearcher;
    }


    private function addPlaceFilter($aFilters, $aPlaces, $bOnlyPlaces = false) {
        $aProvinceFilter = isset($aFilters['province_id']) ? $aFilters['province_id'] : array();
        $aCityFilter = isset($aFilters['city_id']) ? $aFilters['city_id'] : array();
        $aNeighbourhoodFilter = isset($aFilters['neighbourhood_id']) ? $aFilters['neighbourhood_id'] : array();

        $aExcludeCity = array();
        $aExcludeProvince = array();


        if(sizeof($aNeighbourhoodFilter) > 0) {
            $aNeighbourhood = NeighbourhoodPeer::retrieveByPKsJoinAll($aNeighbourhoodFilter);

            foreach($aNeighbourhood as $oNeighbourhood) {
                /**
                 * @var Neighbourhood $oNeighbourhood
                 * @var City $oCity
                 */
                $oCity = $oNeighbourhood->getCity();
                if(!in_array($oCity->getId(), $aExcludeCity)) {
                    $aExcludeCity[] = $oCity->getId();
                }
            }
        }
        if(sizeof($aCityFilter) > 0) {
            $aCity = CityPeer::retrieveByPKs(array_merge($aCityFilter,$aExcludeCity));

            foreach($aCity as $oCity) {
                if(!in_array($oCity->getProvinceId(), $aExcludeProvince)) {
                    $aExcludeProvince[] = $oCity->getProvinceId();
                }
            }
        }
        $aCityFilter = array_diff($aCityFilter, $aExcludeCity);
        $aProvinceFilter = array_diff($aProvinceFilter, $aExcludeProvince);

        if($bOnlyPlaces) {
            $aCityFilter = array();
            $aProvinceFilter = array();
            $aNeighbourhoodFilter = array();
        }



        $aPlaceSelect = array();

        foreach($aNeighbourhoodFilter as $iNeighbourhood) {
            $aPlaceSelect[] = " neighbourhood_id = " . $iNeighbourhood;
        }
        foreach($aCityFilter as $iCity) {
            $aPlaceSelect[] = " city_id = " . $iCity;
        }
        foreach($aProvinceFilter as $iProvince) {
            $aPlaceSelect[] = " province_id = " . $iProvince;
        }


        /** do places */
        foreach($aPlaces as $sType => $aValues) {
            foreach($aValues as $iValue) {
                $aPlaceSelect[] = " ".$sType."_id = " . $iValue;
            }
        }
        /** end places */

        if(sizeof($aPlaceSelect) > 0) {
            $this->aSelectExtras[] = "( " . implode(" OR ", $aPlaceSelect) . " ) as valid_place";
            $this->oSphinx->SetFilter("valid_place", array(1));
        }
    }

    private function crc32($val){
        $checksum = crc32($val);
        if($checksum < 0) $checksum += 4294967296;
        return $checksum;
    }
    
    private function fillEmptyValues($aFilters)
    {
    	if(!array_key_exists('price_min', $aFilters))
    		$aFilters['price_min'] = 0;
    	if(!array_key_exists('price_max', $aFilters))
    		$aFilters['price_max'] = 0;
    	if(!array_key_exists('surface_max', $aFilters))
    		$aFilters['surface_max'] = 0;
    	if(!array_key_exists('rooms_max', $aFilters))
    		$aFilters['rooms_max'] = 0;
    	if(!array_key_exists('types', $aFilters))
    		$aFilters['types'] = array();
    	if(!array_key_exists('schemes', $aFilters))
    		$aFilters['schemes'] = array();
    	if(!array_key_exists('offers', $aFilters))
    		$aFilters['offers'] = array();
    	if(!array_key_exists('associations', $aFilters))
    		$aFilters['associations'] = array();
    	if(!array_key_exists('fixed_filter', $aFilters))
    		$aFilters['fixed_filter'] = array();
    	
    	return $aFilters;
    }

    /** newsletter searches */
    public function newsletterSearch($sType, $iDays = 1) {
        $this->searchQuery = '';

        /** @var Sphinxsearch $sphinxSearch */
        $this->oSearcher->initSphinx();

        $this->oSearcher->setLimit($this->iMax, 0);

        $this->oSearcher->setFilter('category', array(AssociationSaleType::TYPE_SALE));

        if($sType == 'online') {
            if($iDays == 1) {
                $this->oSearcher->setFilterRange('created', strtotime(date('Y-m-d',strtotime("yesterday"))), time());
            } else {
                $this->oSearcher->setFilterRange('created', strtotime(date('Y-m-d',strtotime("-7 days"))), time());
            }
        } else if($sType == 'price') {
            if($iDays == 1) {
                $this->oSearcher->setFilterRange('price_updated', strtotime(date('Y-m-d',strtotime("yesterday"))), time());
            } else {
                $this->oSearcher->setFilterRange('price_updated', strtotime(date('Y-m-d',strtotime("-7 days"))), time());
            }
        } else if($sType == 'publicvisit') {
            if($iDays == 1) {
                $this->oSearcher->setFilterRange('publicvisit_updated', strtotime(date('Y-m-d',strtotime("yesterday"))), time());
            } else {
                $this->oSearcher->setFilterRange('publicvisit_updated', strtotime(date('Y-m-d',strtotime("-7 days"))), time());
            }
        }
        $this->oSearcher->setSortMode(SPH_SORT_ATTR_DESC, 'updated');

        $aResults = $this->oSearcher->search($this->searchQuery, array('VJP'));

        if($aResults['total'] > 0) {
            return $aResults['matches'];
        } else {
            return array();
        }
    }
}

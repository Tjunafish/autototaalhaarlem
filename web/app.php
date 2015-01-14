<?php

use Symfony\Component\ClassLoader\ApcClassLoader;
use Symfony\Component\HttpFoundation\Request;

// echo '<pre>'; var_dump ( __DIR__ ); die();
$loader = require_once __DIR__.'/../app/bootstrap.php.cache';

umask(0000);

// Use APC for autoloading to improve performance.
// Change 'sf2' to a unique prefix in order to prevent cache key conflicts
// with other applications also using APC.
/*
$loader = new ApcClassLoader('sf2', $loader);
$loader->register(true);
*/

if($_SERVER['HTTP_HOST'] == 'ashbeta.webexperienced.nl') {
    if (isset($_SERVER['HTTP_CLIENT_IP'])
        || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
        || !in_array(@$_SERVER['REMOTE_ADDR'],
            array(
                '127.0.0.1',
                'fe80::1',
                '::1',

                '217.122.72.123', /** simon thuis */
                '82.197.210.7',  /** Annemarie */

                '213.127.164.205', /** Michael */

                '83.85.205.208',

                '84.241.199.199',
                '185.6.205.144',


                '82.94.240.8', /** autodata1 */
                '82.94.237.8', /** autodata2 */


            ))
    ) {
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: http://www.autoservicehaarlem.nl" . str_replace('app_dev.php/', '', $_SERVER["REQUEST_URI"]));


        exit;
        header('HTTP/1.0 403 Forbidden');
    }
}



require_once __DIR__.'/../app/AppKernel.php';
//require_once __DIR__.'/../app/AppCache.php';

$kernel = new AppKernel('prod', false);
// $kernel = new AppKernel('dev', true);
$kernel->loadClassCache();
//$kernel = new AppCache($kernel);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);


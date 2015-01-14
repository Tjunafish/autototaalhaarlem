<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

// If you don't want to setup permissions the proper way, just uncomment the following PHP line
// read http://symfony.com/doc/current/book/installation.html#configuration-and-setup for more information
umask(0000);

// This check prevents access to debug front controllers that are deployed by accident to production servers.
// Feel free to remove this, extend it, or make something more sophisticated.
if (isset($_SERVER['HTTP_CLIENT_IP'])
    || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
    || !in_array(@$_SERVER['REMOTE_ADDR'],
        array(
            '127.0.0.1',
            'fe80::1',
            '::1',

            '217.122.70.45', /** simon thuis */
            '84.245.16.141',    /** Annemarie */

            '83.85.205.208',

            '82.94.240.8', /** autodata1 */
            '82.94.237.8', /** autodata2 */


        ))
) {
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: http://www.autoplannederland.nl" . str_replace('app_dev.php/','',$_SERVER["REQUEST_URI"]));


    exit;
    header('HTTP/1.0 403 Forbidden');
    exit('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}

$loader = require_once __DIR__.'/../app/bootstrap.php.cache';
Debug::enable();

require_once __DIR__.'/../app/AppKernel.php';

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);

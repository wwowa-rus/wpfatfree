<?php
//define( 'D_FATFREE', 1 );
require_once("../vendor/autoload.php");
// Set up the base Fat-free class
$f3 = Base::instance();
// Set up the default configuration
$f3->config('../tuning/config.ini');
$f3->config('../tuning/conf_blog.ini');
// Set up the routes
$f3->config('../tuning/routes.ini');
// Setting LANGUAGE
$f3->set('LANGUAGE','ru');
$f3->set('CACHE','folder=tmp/cache/');
// Setting up the database for session management
$database = new DB\SQL(
    $f3->get('DB'),
    $f3->get('DBUSER'),
    $f3->get('DBPASSWORD'),
    array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION)
);
// Setup generic error page. Disable if you need an error stacktrace.
/* $f3->set('ONERROR',
    function($f3) {
        $template = new Template();
        echo $template->render('theme/error.htm');
    }
); */
$f3->set('ONERROR', 'ErrorController->errorpage');
// Setting up the database for session management
new Session(NULL,'CSRF');
$f3->copy('CSRF','SESSION.csrf');

 // Run the 'aaa'application
$f3->run();

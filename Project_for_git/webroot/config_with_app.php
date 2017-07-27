<?php
/**
 * Config file for pagecontrollers, creating an instance of $app.
 *
 */

// Get environment & autoloader.
require __DIR__.'/config.php';

// Create services and inject into the app.
$di  = new \Anax\DI\CDIFactoryDefault();

// Set the commentController, a ready package to use , that handles comments.
// You can get it from packagist and also published on Git
$di->set('CommentController', function () use ($di) {
    $controller = new Phpmvc\Comment\CommentController();
    $controller->setDI($di);
    return $controller;
});

//and then set the database
$di->setShared('db', function () {
    $db = new \Mos\Database\CDatabaseBasic();
    $db->setOptions(require ANAX_APP_PATH . 'config/database_sql.php');
    $db->connect();
    return $db;
});

$app = new \Anax\MVC\CApplicationBasic($di);

//Lägg till CForm också
$di->setShared('form', function () {
    $form = new \Mos\HTMLForm\CForm();
    return $form;
});

$di->set('FormController', function () use ($di) {
    $controller = new \Anax\HTMLForm\FormController();
    $controller->setDI($di);
    return $controller;
});


$di->set('FormSmallController', function () use ($di) {
    $controller = new \Anax\HTMLForm\FormSmallController();
    $controller->setDI($di);
    return $controller;
});

//Lägg till user controller
$di->set('UsersController', function () use ($di) {
    $controller = new \Anax\Users\UsersController();
    $controller->setDI($di);
    return $controller;
});

//Lägg till comments controller
$di->set('Comments2Controller', function () use ($di) {
    $controller = new \Anax\Comments\Comments2Controller();
    $controller->setDI($di);
    return $controller;
});

//lägg till gallery controller
$di->set('GalleryController', function () use ($di) {
    $controller = new \Anax\Gallery\GalleryController();
    $controller->setDI($di);
    return $controller;
});

//lägg till users2controller
$di->set('Users2Controller', function () use ($di) {
    $controller = new \Anax\Users2\Users2Controller();
    $controller->setDI($di);
    return $controller;
});


//Lägg till questionController
$di->set('QuestionsController', function () use ($di) {
    $controller = new \Anax\Questions\QuestionsController();
    $controller->setDI($di);
    return $controller;
});

//Lägg till answerController
$di->set('AnswersController', function () use ($di) {
    $controller = new \Anax\Answers\AnswersController();
    $controller->setDI($di);
    return $controller;
});

//Lägg till comments3Controller
$di->set('Comments3Controller', function () use ($di) {
    $controller = new \Anax\Comments3\Comments3Controller();
    $controller->setDI($di);
    return $controller;
});

//Lägg till commentsOnComments
$di->set('ComController', function () use ($di) {
    $controller = new \Anax\Com\ComController();
    $controller->setDI($di);
    return $controller;
});

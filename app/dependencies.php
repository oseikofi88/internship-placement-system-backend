<?php
// DIC configuration

$container = $app->getContainer();

// -----------------------------------------------------------------------------
// Service providers
// -----------------------------------------------------------------------------


//database stuff
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function ($container) {
    return $capsule;
};


// -----------------------------------------------------------------------------
// Service factories
// -----------------------------------------------------------------------------

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings');
    $logger = new Monolog\Logger($settings['logger']['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['logger']['path'], Monolog\Logger::DEBUG));
    return $logger;
};

//

// -----------------------------------------------------------------------------
// Action factories
// -----------------------------------------------------------------------------


//students
$container['Student'] = function ($c) {
    return new App\Controllers\StudentController();
};


//companies
$container['Company'] = function ($c) {
    return new App\Controllers\CompanyController();
};


//admin
$container['Admin'] = function ($c) {
    return new App\Controllers\AdminController();
};

//coordinator
$container['Coordinator'] = function ($c){
    return new App\Controllers\CoordinatorController();
};


$container['Test'] = function ($c) {
    return new App\Controllers\TestController();
};



//Departments
$container['MainDepartment'] = function ($c) {
    return new App\Controllers\MainDepartmentController();

};

//SubDepartment
$container['SubDepartment'] = function ($c) {
    return new App\Controllers\SubDepartmentController();

};

$container['Location'] = function ($c){
    return new App\Controllers\LocationController();
};


//Placements 
$container['PlacementStatus'] = function ($c) {
    return new App\Controllers\PlacementStatusController();

};

//User
$container['User'] = function ($c) {
    return new App\Controllers\UserController();

};

//StudentStatistics
$container['StudentStatistics'] = function ($c) {
    return new App\Controllers\StudentStatisticsController();

};

//CompanyStatistics
$container['CompanyStatistics'] = function ($c) {
    return new App\Controllers\CompanyStatisticsController();

};


<?php
// Routes
//students routes
$app->post('/student/register', 'Student:registerStudent');
$app->post('/student/update-details', 'Student:updateStudentDetails');
$app->post('/student/login', 'Student:loginStudent');
$app->get('/student/all', 'Student:getAllStudents');
$app->get('/student/check', 'Student:getStudent');
$app->post('/student/reject-placement','Student:rejectPlacement');
$app->post('/student/update-supervisor-details','Student:updateSupervisorAndCompanyLocationDetails');
$app->post('/student/company/register','Student:registerCompany');
$app->post('/student/company/make-order','Student:orderForCompany');
$app->post('/student/registered-companies','Student:getListOfRegisteredCompanies'); 
$app->get('/student/started-internship','Student:recordTimeStudentStartedInternship');
$app->post('/student/upload-acceptance-letter','Student:uploadAcceptanceLetter');
$app->post('/student/send-password-reset-link', 'Student:sendPasswordResetLink');
$app->post('/student/reset-password', 'Student:resetPassword');
//company Routes

$app->post('/company/register', 'Company:registerCompany');
$app->post('/company/make-student-order', 'Company:makeStudentOrder');
$app->get('/company/details', 'Company:getCompanyDetails');
$app->get('/company/students-placed-in-company-details', 'Company:studentsPlacedInCompanyDetails');
$app->post('/company/login', 'Company:loginCompany');
$app->get('/company/all-companies-in-coordinator-department', 'Company:getAllCompaniesPerDepartment');
$app->get('/company/all', 'Company:getAllCompaniesRegistered');


//admin routes
$app->post('/admin/login', 'Admin:loginAdmin');
$app->post('/admin/register-company','Admin:registerCompany');
$app->post('/admin/register-company-and-make-order','Admin:registerCompanyAndMakeOrder');
$app->get('/admin/place-students', 'Admin:placeStudents');
$app->post('/place-students-in-company', 'Admin:placeStudentsInCompanyManually'); 
$app->post('/admin/reject-student-placement', 'Admin:rejectStudentsPlacement'); 
$app->post('/admin/company/make-order','Admin:orderForCompany');
$app->post('/admin/register-coordinator','Admin:registerCoordinator');
$app->post('/admin/add-new-admin','Admin:addNewAdmin');
$app->get('/admin/check', 'Admin:checkIfAdminUsernameAlreadyExist');
$app->get('/admin/undo-placement', 'Admin:undoAllPlacement');
$app->get('/admin/all-students-and-company-details', 'Admin:getAllStudentsAndCompanyDetails');

//coordinator routes
$app->post('/coordinator/login', 'Coordinator:loginCoordinator');
$app->get('/coordinator/check', 'Coordinator:getCoordinator');
$app->get('/coordinator/all', 'Coordinator:getAllCoordinators');
$app->post('/coordinator/replace','Coordinator:replaceCoordinator');


//Department Routes
$app->get('/main-department/all', 'MainDepartment:getAllMainDepartments');

//SubDepartment Routes
$app->get('/sub-department/all', 'SubDepartment:getAllSubDepartments');

//placement status route

$app->get('/placement_status/check','PlacementStatus:checkIfPlacementIsDone');

//shared routes
$app->post('/file-concern', 'User:forwardConcernToCoordinator');
$app->get('/placement-status', 'User:getPlacementStatus');
$app->get('/send-email', 'User:sendStudentPasswordResetLink');

$app->get('/general-student-statistics', 'StudentStatistics:getGeneralStudentStatistics');
$app->post('/departmental-student-statistics', 'StudentStatistics:getDepartmentalStudentStatistics');
$app->post('/departmental-students-statistics/details', 'StudentStatistics:getDepartmentalStudentsDetailsStatistics');
$app->get('/general-student-graph-statistics', 'StudentStatistics:getGeneralStudentGraphStatistics');
$app->get('/departmental-student-graph-statistics', 'StudentStatistics:getDepartmentalStudentGraphStatistics');
$app->get('/departmental-student-regional-graph-statistics', 'StudentStatistics:getDepartmentalStudentRegionalGraphStatistics');
$app->get('/general-regional-student-graph-statistics', 'StudentStatistics:getGeneralRegionalStudentGraphStatistics');

$app->get('/general-company-statistics', 'CompanyStatistics:getGeneralCompanyStatistics');
$app->post('/departmental-company-statistics', 'CompanyStatistics:getDepartmentalCompanyStatistics');
$app->get('/general-company-graph-statistics', 'CompanyStatistics:getGeneralCompanyGraphStatistics');
$app->get('/departmental-company-graph-statistics', 'CompanyStatistics:getDepartmentalCompanyGraphStatistics');
$app->post('/departmental-company-statistics/details', 'CompanyStatistics:getDepartmentalCompanyDetailsStatitistics');



$app->get('/faker/student-register','Test:register1000Students');
$app->get('/testss/fill-null-locations','Test:fillNullLocations');
$app->get('/faker/company-register','Test:register1000Companies');
$app->get('/testss','Test:getSelectedCompaniesWithMatchingCriteria');
$app->get('/chrome-php','Test:chromePhp');

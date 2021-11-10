<?php
// Starting Session 
// header("Access-Control-Allow-Origin: https://teamwork-c288a.web.app");
header('Access-Control-Allow-Origin: http://localhost:3000');
// Starting Session 
$path = 'https://teamwork-c288a.web.app';
         session_set_cookie_params(
            $path,
            $currentCookieParams["secure"],
            $currentCookieParams["httponly"]
         );
session_start();
Date_default_timezone_set('Australia/Brisbane');
$data = file_get_contents("php://input");
include "session.php";
$sess = new sessionManager();



// IS logged in
//Note where you are checking if a session pre-exists, what are you doing if it does.

//Function is called by fetch on every load of page, if false a 401 is sent to client and page is rendered accordingly, to show client needs to log in. 
function isUserLoggedIn()
{
    if (!isset($_SESSION['email'])) {
        return false;
    }else {
        return true;
    }
}


// Rate limiting requests to 1 a sec
// $sess->is_rate_limited();

// Is correct origin ??
// $sess->is_corret_origin();
header('Access-Control-Allow-Origin: http://localhost:3000');
// header("Access-Control-Allow-Origin: https://teamwork-c288a.web.app");
// header('Access-Control-Allow-Origin: https://8f24-138-44-128-242.ngrok.io');
header("Access-Control-Allow-Credentials: true");







// Setting a new time variable to call on
$freshMeat = new DateTime();

// Session counting 
$sess->counter();

// Session request limit 1,000 in a 24hour period
$sess->limit_request($sess->counter, $freshMeat);

// Sanitise inputs
$sess->test_input($data);

include "connection.php";
$dbcon = new DB();
// Setting some values for logging table
$user_action = $_SERVER["REQUEST_METHOD"];
$req_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$sess_num = session_id();
$ipaddress = $_SERVER['REMOTE_ADDR'];





// Sanitise inputs
$sess->test_input($data);
// Note where you are checking if a session pre-exists, what are you doing if it does



header('Content-Type: application/json');
$resp_body = array();
$resp_code = 500;



//------------------------------Switch Case----------------------------
if (isset($_GET['action'])) {
    switch ($_GET['action']) {



            // Testing Javascript fetch

        case 'test':

            echo ('hello');
            $resp_code = 200;
            $resp_body = array('test' => 'true');
            break;


        case 'endtest':
            if (isset($_SESSION["employees_idNumber"])) {
                $dbcon->endTest($_SESSION["employees_idNumber"]);
                logout();
                $resp_code = 200;
            } else {
                $resp_code = 401;
            }
            break;

        case 'isLoggedin':

            // Checking if $_SESSION["employees_idNumber"] has been set, if not 401 Unauthorized is returned
            if (isset($_SESSION["employees_idNumber"])) {
                if (isUserLoggedIn()) {
                    $resp_code = 202;
                } if(($_SESSION['is_manager'] == true)){
                    isUserLoggedIn();
                    $resp_code = 307;
                }
                else {
                    $resp_code = 401;
                }
            } else {
                $resp_code = 401;
            }
            break;

            // New register to app
        case 'register':

            // Checking if $_SESSION["employees_idNumber"] has been set, if not 401 Unauthorized is returned
            if (!isset($_SESSION["employees_idNumber"])) {

                $email = $_POST['email'];
                $firstName = $_POST['firstName'];
                $lastName = $_POST['lastName'];
                $department = $_POST['department'];
                $phone_number = $_POST['phone_number'];
                $clockN = $_POST['clockInNum'];
                $pword = $_POST['pword'];
                $DOB = $_POST['DOB'];
               
                //Sanitising inbound data
                $sess->test_input($_POST);

                if (
                    $sess->validation_pword($pword) && $sess->validation_name($firstName, $lastName) && $sess->validation_Clockon($clockN) && $sess->validation_department($department) && $sess->validation_email($email) && $sess->validation_phone_number($phone_number) && $sess->validation_DOB($DOB)

                ) {
     
                    // Checking that $email is FILTER_VALIDATE_EMAIL
                    if ($sess->emailCheck($email)) {
                        if ($dbcon->register_new($firstName, $lastName, $email, $department, $phone_number, $clockN, $pword, $DOB)) {
                            $resp_code = 201;
                   
                        } else {
                            $resp_code = 403;
                        
                        }
                    } else {
                        $resp_code = 401;
                     
                    }
                } else {
                    $resp_code = 401;
                    $resp_body = array('register' => 'false');
                }
            } else {
                $resp_code = 401;
                $resp_body = array('register' => 'false');
            }
            break;



            //Login into TeamWork
        case 'login':
            
      
            $email = $_POST['email'];
            $pword = $_POST['pword'];
            //Sanitising inbound data
            $sess->test_input($pword, $email);
            // Validating inbound $_post data
      
            if ($sess->emailCheck($email) && $sess->validation_email($email) && $sess->validation_pword($pword)) {
            
                if ($dbcon->todoLogin($email, $pword) === 1) {
                    $resp_code = 202;
         
                } else if ($dbcon->todoLogin($email, $pword) === 2) {
                    $resp_code = 307;
            
                }
                else {
                    $resp_code = 401;
                }
            } else {
                $resp_code = 401;
            }
            
            
            break;


            //LogOut of Team Work
        case 'logout':
            $resp_code = 202;
            logOut();
            break;

            //View personal Details
        case 'viewMyDetails':
            // Checking if $_SESSION["employees_idNumber"] has been set, if not 401 Unauthorized is returned
            if (isset($_SESSION['employees_idNumber'])) {
                $w = $dbcon->viewMyDetails($_SESSION['employees_idNumber']);
                $resp_code = 202;
                echo json_encode($w);
            } else {
                $resp_code = 401;
            }
            break;

            //Update personal details
        case 'upDateMyDetails':
            // Checking if $_SESSION["employees_idNumber"] has been set, if not 401 Unauthorized is returned
            if (isset($_SESSION['employees_idNumber'])) {
                $email = $_POST['email'];
                $firstName = $_POST['firstName'];
                $lastName = $_POST['lastName'];
                $department = $_POST['department'];
                $phone_number = $_POST['phone_number'];
                $clockN = $_POST['clock_Number'];
                $DOB = $_POST['DOB'];

                //Sanitising inbound data
                $sess->test_input($_POST);
                if ($sess->validation_name($firstName, $lastName) && $sess->validation_Clockon($clockN) && $sess->validation_department($department) && $sess->validation_email($email) && $sess->validation_phone_number($phone_number)) {
                    if ($dbcon->upDateMyDetails($firstName, $lastName, $email, $department, $phone_number, $clockN,$DOB, $_SESSION['employees_idNumber'])) {
                        $resp_code = 201;
                    } else {
                        $resp_code = 401;
                   
                    }
                } else {
                    $resp_code = 401;
             
                }
            } else {
                $resp_code = 401;
           
            }
            break;


            //Delete personal details
        case 'deleteDetails':
            // Checking if $_SESSION["employees_idNumber"] has been set, if not 401 Unauthorized is returned
            if (isset($_SESSION['employees_idNumber'])) {
                $resp_code = 200;
                $resp_body = array('test' => 'true');
            } else {
                $resp_code = 401;
                $resp_body = array('test' => 'true');
            }
            break;

            // View schedule
        case 'viewMyAvail':
            // Checking if $_SESSION["employees_idNumber"] has been set, if not 401 Unauthorized is returned
            if (isset($_SESSION['employees_idNumber'])) {
                $viewMyAvail = $dbcon->viewMyAvail($_SESSION['employees_idNumber']);
                if ($viewMyAvail === false) {
                    $resp_code = 307;
                } else {
                    $resp_code = 202;
                    echo json_encode($viewMyAvail);
                }
            } else {
                $resp_code = 401;
            }
            break;

            // Insert My Avail

        case 'insertMy_Avail':
            // Checking if $_SESSION["employees_idNumber"] has been set, if not 401 Unauthorized is returned
            if (isset($_SESSION['employees_idNumber'])) {
                //Sanitising inbound data
                $sess->test_input($_POST);
                // validation_days is checking days correct strings
                $sess->validation_days($_POST);
                if ($dbcon->insertMy_Avail($_POST['Monday'], $_POST['Tuesday'], $_POST['Wednesday'], $_POST['Friday'], $_POST['Thursday'], $_POST['Saturday'], $_POST['Sunday'], $_SESSION['employees_idNumber'])) {
                    $resp_code = 201;
                    $resp_body = array('test' => 'true');
                } else {
                    $resp_code = 401;
                    $resp_body = array('test' => 'false');
                }
            } else {
                $resp_code = 401;
            }

            break;

        case 'updateMyAvail':
            if (isset($_SESSION['employees_idNumber'])) {
                //Sanitising inbound data
                $sess->test_input($_POST);
                // validation_days is checking days correct strings
                $sess->validation_days($_POST);
                if ($dbcon->updateMyAvail($_POST['Monday'], $_POST['Tuesday'], $_POST['Wednesday'], $_POST['Friday'], $_POST['Thursday'], $_POST['Saturday'], $_POST['Sunday'], $_SESSION['employees_idNumber'])) {
                    $resp_code = 201;
                } else {

                    $resp_code = 401;
                }
            } else {
                $resp_code = 401;
            }
            break;


            //Delete personal Avail
        case 'deletemyAvail':

            if (isset($_SESSION['employees_idNumber'])) {
                $dbcon->deletemyAvail($_SESSION['employees_idNumber']);
                $resp_code = 200;
            } else {
                $resp_code = 401;
                $resp_body = array('test' => 'true');
            }
            break;
       

        case 'viewClock':
            // Checking if $_SESSION["employees_idNumber"] has been set, if not 401 Unauthorized is returned
            if (isset($_SESSION['employees_idNumber'])) {
                if ($dbcon->viewLastPunch($_SESSION['employees_idNumber']) == true) {
                    $viewClock = $dbcon->viewLastPunch($_SESSION['employees_idNumber']);
                    echo json_encode($viewClock);
                    $resp_code = 200;
                } else {
                    $resp_code = 204;
                }
            } else {
                $resp_code = 401;
            }
            break;

            case"viewMySchedule":
                if(isset($_SESSION['employees_idNumber'])){
            
                $mySchedule=$dbcon->viewMyScheduleMaker($_SESSION['employees_idNumber']);
                echo json_encode($mySchedule);

                $resp_code = 202;
                } else {
                    $resp_code = 401;

                }
                break;


        case 'clockIn':
            // Checking if $_SESSION["employees_idNumber"] has been set, if not 401 Unauthorized is returned
            if (isset($_SESSION['employees_idNumber'])) {
                $clockinN = $_POST["clockInNumber"];
                //Sanitising inbound data
                $sess->test_input($_POST);
                // validation_Clockon is ensuring valid staff number is used 
                if ($sess->validation_Clockon($clockinN)) {
                    if ($dbcon->checkClockNumber($clockinN, $_SESSION['employees_idNumber'])) {
                        if ($dbcon->checkClock($_SESSION['employees_idNumber'])) {
                            $dbcon->punchOutClock($_SESSION['employees_idNumber']);
                            $resp_code = 202;
                        } else {
                            $dbcon->punchInClock($clockinN, $_SESSION['employees_idNumber']);
                            $resp_code = 201;
                        }
                    } else {
                        $resp_code = 401;
                    }
                } else {
                    $resp_code = 401;
                }
            } else {
                $resp_code = 401;
            }
            break;
           

        ///// ADMIN


///// Updating employee admin side
        case "upDateEmployee":
            if ($_SESSION['is_manager'] == true){
                //Decoding json data from admin
                $request_body = file_get_contents("php://input");
                $objreg = json_decode($request_body ,true);
                error_log($request_body);
                error_log(print_r($objreg, true));
                $employees_idNumber = $objreg["employees_idNumber"];
                $firstName = $objreg["firstName"];
                $lastName = $objreg["lastName"];
                $email = $objreg["email"];
                $department = $objreg['department'];
                $phone_number = $objreg['phone_number'];
                $clockInNum = $objreg['clockInNum'];
                $DOB = $objreg['DOB'];
                $is_manager = $objreg['is_manager'];
             

                if(
                $sess->validation_name_ADMIN($objreg["firstName"])&&
                $sess->validation_name_ADMIN($objreg["lastName"])&&
                $sess->validation_department($objreg['department'])&&
                $sess->validation_phone_number($objreg['phone_number'])&&
                $sess->validation_Clockon($objreg['clockInNum'])&&
                $sess->validation_DOB($objreg['DOB'])
            )
                
                {
                    // error_log($request_body);
                    // error_log(print_r($objreg, true));
                     //Sanitising inbound data
                    // $sess->test_input($_POST);
                    $dbcon->upDateEmployee($firstName, $lastName,$email, $department , $phone_number, $clockInNum, $DOB, $employees_idNumber);
                    $resp_code = 201;

                } else{
                    $resp_code = 401;
            } 
        }else{
            error_log(2);
                $resp_code = 401;
        }
            break;
            // List of all Employees in database
            case 'viewAllEmployees':
                if ($_SESSION['is_manager'] === true) {
                            echo json_encode($dbcon->viewAllEmployees());
                            $resp_code = 202;
                            } else {
                                $resp_code = 401;
                            }
            break;



            ///// Adding new employee admin side
            case "addNewEmployee":
                if ($_SESSION['is_manager'] === true){

                //Decoding json data from admin
                $request_body = file_get_contents("php://input");
                $objreg = json_decode($request_body ,true);
                $firstName = $objreg['userInput']["firstName"];
                $lastName = $objreg['userInput']["lastName"];
                $email = $objreg['userInput']["email"];
                $pword = $objreg['userInput']["pword"];
                $department = $objreg['userInput']['department'];
                $phone_number = (int)$objreg['userInput']['phone_number'];
                $clockInNum = (int)$objreg['userInput']['clockInNum'];
                $DOB = $objreg['userInput']['DOB'];
                $is_manager = $objreg['userInput']['is_manager'];
                // $firstName = $_POST["firstName"];
                if
                ($sess->validation_name_ADMIN($objreg['userInput']["firstName"])&&
                $sess->validation_name_ADMIN($objreg['userInput']["lastName"]) &&
                $sess->validation_department($objreg['userInput']['department']) &&
                $sess->validation_phone_number((int)$objreg['userInput']['phone_number'])&&
                $sess->validation_Clockon((int)$objreg['userInput']['clockInNum'])&&
                $sess->validation_DOB($objreg['userInput']['DOB'])){
                }
                    //Sanitising inbound data
                    $sess->test_input($_POST);
                    //Add new Employee
                    $dbcon->addNewEmployee($firstName, $lastName,  $email, $department, $phone_number,$is_manager, $clockInNum, $pword, $DOB);
                    $resp_code = 201;
                    } else{
                    $resp_code = 401;
    
                } 
                break;

                ///// Deleting employee admin side
            case "deleteEmployee":
                if ($_SESSION['is_manager'] === true){  
                       //Decoding json data from admin
                $request_body = file_get_contents("php://input");
                $objreg = json_decode($request_body ,true);
                    if(($_SESSION['employees_idNumber']) == ($objreg['Employee_id'])){
                        $resp_code = 401;
                    } else{
                        $id = ($objreg['Employee_id']);
                        $dbcon->deleteEmployee($id);
                        $resp_code = 202;
                    }      
                }

                break;
                     // Creating new schedule only by manager
        case 'addSchedule':

            if ($_SESSION['is_manager'] == true){
                $request_body = file_get_contents("php://input");
                $objreg = json_decode($request_body ,true);
                      error_log($request_body);
                      error_log(print_r($objreg, true));
            
                  //Decoding json data from admin
                  $startDate_Time = $objreg['scheduleDataFinal'][0];
                  $finishDate_Time = $objreg['scheduleDataFinal'][1];
                  $department = $objreg['scheduleDataFinal'][2];
                  $employees_idNumber = $objreg['scheduleDataFinal'][3];
                  $shiftMsg = $objreg['scheduleDataFinal'][4];
                  $dbcon->createSchedule( $employees_idNumber,$department, $startDate_Time,$finishDate_Time,$shiftMsg);
               $resp_code = 201;
            }else{
                $resp_code = 401;
            }

            break;
                   // Update schedule only by manager
        case 'updateSchedule':


            echo ('updateDetails is good');
            $resp_code = 209;
            $resp_body = array('test' => 'true');

            echo ('update schedule is bad');
            $resp_code = 409;
            $resp_body = array('test' => 'true');

            break;


        case"viewFullSchedule":
            // if ($_SESSION['is_manager'] == true) {
                echo json_encode($dbcon->viewFullSchedule());
                $resp_code = 202;
                // } else {
                //     $resp_code = 401;
                // }
            break;


        default:
            $resp_code = 501;
            $resp_body = array('test' => 'False');
            //
    }
};

/////------- LOGGING SESSION/REQUESTS TO DB

http_response_code($resp_code);
// Setting Final Values for logging table


///Logging actions taken
if (isset($_SESSION["employees_idNumber"])) {
    // Setting Final Values for logging table
    $firstName = $_SESSION['firstName'];
    $id = $_SESSION["employees_idNumber"];
    $dbcon->log_sessions($req_url, $ipaddress, $user_action, $resp_code, $sess_num, $firstName, $id);
} else {
    $dbcon->log_sessions_no_id($req_url, $ipaddress, $user_action, $resp_code, $sess_num);
}





// // LOG OUT
function logOut()
{
    $_SESSION['email'] = false;
    session_unset();
    session_destroy();
}
// error_log($request_body);
// error_log(print_r($objreg, true));
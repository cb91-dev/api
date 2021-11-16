<?php

class DB
{

    private $classTeamWork;

    // 13.Denote where and explain why you instantiated the database and session objects in that location
    // __construct function is placed at start of object as it needs to be delcare before it can be called in below function 


    //Function to establish connection with database
    public function __construct()
    { 
        // $user_name = "bennettd_teamWork_admin";
        // $password = "Philisgreat#101";
        // //define the data source name 
        // $dbURI = 'mysql:host=108.61.169.233;port=3306;dbname=bennettd_teamWork_api';
        $user_name = "teamWork";
        $password = "password";
        //define the data source name 
        $dbURI = 'mysql:host=127.0.0.1;port=8889;dbname=teamWork';
        $this->dbcon = new PDO($dbURI, $user_name, $password);
        $this->dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    /////
    public function endTest($employees_idNumber)
    {
        $sql = "DELETE FROM api_logs WHERE employees_idNumber = $employees_idNumber";
        $stmt = $this->dbcon->prepare($sql);
        $stmt->execute();
        $sql = "DELETE FROM Live_Clock WHERE employees_idNumber = $employees_idNumber";
        $stmt = $this->dbcon->prepare($sql);
        $stmt->execute();
        $sql = "DELETE FROM Employees WHERE employees_idNumber = $employees_idNumber";
        $stmt = $this->dbcon->prepare($sql);
        $stmt->execute();
    }


    ////------------------------- LOGIN USER/////
    public function todoLogin($email, $pword)
    {
        $sql = "SELECT * FROM Employees WHERE email = :email";
        $stmt = $this->dbcon->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($stmt->rowCount() == 1) {
            if (password_verify($pword, $row['pword'])){
            if($row['is_manager'] == 1) {
            //  manger stuff
            $_SESSION['email'] =  $row["email"];
            $_SESSION['is_manager'] =  true;
            $_SESSION['firstName'] = $row["firstName"];
            $_SESSION["employees_idNumber"] = $row["employees_idNumber"];
             return 2;
            }
            else{
                $_SESSION['email'] =  $row["email"];
                $_SESSION['firstName'] = $row["firstName"];
                $_SESSION["employees_idNumber"] = $row["employees_idNumber"];
                return 1;
            }
        } return 0;
            return 0;
        }return 0;
    }



    ////------------------Registering new users/////

    public function register_new($fN, $lN, $e, $Dep, $pN, $clockN, $p, $DOB)
    {

        $sql = "INSERT INTO Employees (firstName,lastName,email, department, phone_number,clock_Number,pword,DOB) 
                VALUES (:firstName,:lastName,:email,:department, :phone_number,:clock_Number, :pword,:dob)";

    //// PASSWORD IS HASHED HERE ///////
        $p = password_hash($p,PASSWORD_DEFAULT);
        
        $stmt = $this->dbcon->prepare($sql);
        $stmt->bindParam(':firstName', $fN, PDO::PARAM_STR);
        $stmt->bindParam(':lastName', $lN, PDO::PARAM_STR);
        $stmt->bindParam(':email', $e, PDO::PARAM_STR);
        $stmt->bindParam(':department', $Dep, PDO::PARAM_STR);
        $stmt->bindParam(':phone_number', $pN, PDO::PARAM_INT);
        $stmt->bindParam(':clock_Number', $clockN, PDO::PARAM_INT);
        $stmt->bindParam(':pword', $p, PDO::PARAM_STR);
        $stmt->bindParam(':dob', $DOB, PDO::PARAM_STR);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->execute();
        error_log(print_r($row)); 
        if ($stmt->rowCount() === 1) {
          return true;
        } else{
            return false;
        }
    }

    public function log_sessions($req_url, $ipaddress, $user_action, $resp_code, $sess_num, $firstName, $idNumber)
    {
        $sql = "INSERT INTO api_logs (req_url, ip_address, user_action, response_code, session_num, users_firstName, employees_idNumber) 
        VALUES (:req_url,:ip_address,:user_action,:response_code,:sess_num,:firstName,:employees_idNumber)";
        $stmt = $this->dbcon->prepare($sql);
        $stmt->bindParam(':req_url', $req_url, PDO::PARAM_STR);
        $stmt->bindParam(':ip_address', $ipaddress, PDO::PARAM_STR);
        $stmt->bindParam(':user_action', $user_action, PDO::PARAM_STR);
        $stmt->bindParam(':response_code', $resp_code, PDO::PARAM_STR);
        $stmt->bindParam(':sess_num', $sess_num, PDO::PARAM_STR);
        $stmt->bindParam(':firstName', $firstName, PDO::PARAM_STR);
        $stmt->bindParam(':employees_idNumber', $idNumber, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }
    // Loggin action when no is logged in 
    public function log_sessions_no_id($req_url, $ipaddress, $user_action, $resp_code, $sess_num)
    {
        $sql = "INSERT INTO api_logs (req_url, ip_address, user_action, response_code, session_num) 
        VALUES (:req_url,:ip_address,:user_action,:response_code,:sess_num)";
        $stmt = $this->dbcon->prepare($sql);
        $stmt->bindParam(':req_url', $req_url, PDO::PARAM_STR);
        $stmt->bindParam(':ip_address', $ipaddress, PDO::PARAM_STR);
        $stmt->bindParam(':user_action', $user_action, PDO::PARAM_STR);
        $stmt->bindParam(':response_code', $resp_code, PDO::PARAM_STR);
        $stmt->bindParam(':sess_num', $sess_num, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }


    ///---- Viewing details for Team member ----///
    public function viewMyDetails($employees_idNumber)
    {
        $sql = "SELECT *
        FROM Employees WHERE employees_idNumber = $employees_idNumber";
        $stmt = $this->dbcon->prepare($sql);
        $stmt->execute(array());
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res;
        return true;
    }
    public function upDateMyDetails($fN, $lN, $e, $Dep, $phoneN, $clockN, $DOB, $employees_idNumber)
    {
        // if ($this->does_email_exist($e) == true)
        $sql = "UPDATE Employees SET firstName= '$fN', lastName= '$lN', email= '$e', department= '$Dep', phone_number= '$phoneN', clock_Number= '$clockN', DOB= '$DOB' WHERE employees_idNumber = '$employees_idNumber'";
        $stmt = $this->dbcon->prepare($sql);
        $stmt->bindParam(':firstName', $fN, PDO::PARAM_STR);
        $stmt->bindParam(':lastName', $lN, PDO::PARAM_STR);
        $stmt->bindParam(':email', $e, PDO::PARAM_STR);
        $stmt->bindParam(':department', $Dep, PDO::PARAM_STR);
        $stmt->bindParam(':phone_number', $phoneN, PDO::PARAM_STR);
        $stmt->bindParam(':clock_Number', $clockN, PDO::PARAM_INT);
        $stmt->bindParam(':dob', $DOB, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            return true;
        }
        return false;
    }
    ///-----------------------Insert New Availabilities ---------////////

    public function insertMy_Avail($monA, $tueA, $wedA, $thursA, $friA, $satA, $sunA, $employees_idNumber)
    {
        $sql = "INSERT INTO availabilities (Monday, Tuesday, Wednesday, Thursday, Friday, Saturday, Sunday,employees_idNumber)
        VALUES ('$monA', '$tueA', '$wedA', '$thursA', '$friA','$satA', '$sunA', '$employees_idNumber')";
        $stmt = $this->dbcon->prepare($sql);
        $stmt->bindParam(':Monday', $monA, PDO::PARAM_STR);
        $stmt->bindParam(':Tuesday', $tueA, PDO::PARAM_STR);
        $stmt->bindParam(':Wednesday', $wedA, PDO::PARAM_STR);
        $stmt->bindParam(':Thursday', $thursA, PDO::PARAM_STR);
        $stmt->bindParam(':Friday', $friA, PDO::PARAM_STR);
        $stmt->bindParam(':Saturday', $satA, PDO::PARAM_STR);
        $stmt->bindParam(':Sunday', $sunA, PDO::PARAM_STR);
        $stmt->bindParam(':Em', $employees_idNumber, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            return true;
        }
        return false;
    }
    public function updateMyAvail($monA, $tueA, $wedA, $thursA, $friA, $satA, $sunA, $employees_idNumber)
    {
        $sql = "UPDATE availabilities SET Monday= '$monA', Tuesday= '$tueA', Wednesday= '$wedA', Thursday= '$thursA', Friday= '$friA', Saturday= '$satA',Sunday= '$sunA' WHERE employees_idNumber = '$employees_idNumber'";
        $stmt = $this->dbcon->prepare($sql);
        $stmt->bindParam(':Monday', $monA, PDO::PARAM_STR);
        $stmt->bindParam(':Tuesday', $tueA, PDO::PARAM_STR);
        $stmt->bindParam(':Wednesday', $wedA, PDO::PARAM_STR);
        $stmt->bindParam(':Thursday', $thursA, PDO::PARAM_STR);
        $stmt->bindParam(':Friday', $friA, PDO::PARAM_STR);
        $stmt->bindParam(':Saturday', $satA, PDO::PARAM_STR);
        $stmt->bindParam(':Sunday', $sunA, PDO::PARAM_STR);
        $stmt->execute();
        return true;
        // if ($stmt->rowCount() == 1) {
        //     return true;
        // } else {
        //     return false;
        // }
    }
    public function viewMyAvail($employees_idNumber)
    {
        $sql = "SELECT *
        FROM availabilities WHERE employees_idNumber = $employees_idNumber";
        $stmt = $this->dbcon->prepare($sql);
        $stmt->execute(array());
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res;
    }
    public function deletemyAvail($employees_idNumber)
    {
        $sql = "DELETE FROM availabilities WHERE employees_idNumber = $employees_idNumber";
        $stmt = $this->dbcon->prepare($sql);
        $stmt->execute();
        return true;
    }

    /////--------------- LIVE CLOCK 

    // checking inputted clock in number mataches employee number 
    public function checkClockNumber($clockinN, $employees_idNumber)
    {
        $sql = "SELECT clock_Number, employees_idNumber FROM Employees WHERE employees_idNumber = $employees_idNumber";
        $stmt = $this->dbcon->prepare($sql);
        $stmt->execute(array());
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($res['clock_Number'] === $clockinN) {
            return true;
        } else {
            return false;
        }
    }
    //Check clock function
    public function checkClock($employees_idNumber)
    {
        $sql = "SELECT * FROM Live_Clock WHERE employees_idNumber = $employees_idNumber
        ORDER by clockIn DESC
        LIMIT 1";
        $stmt = $this->dbcon->prepare($sql);
        $stmt->execute(array());
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($res) == 0) {
            return false;
        }
        if ($res[0]['clockOut'] != null) {
            return false;
            // insert
        } else {
            return true;
            // update
        }
    }


//Clock out function
    public function punchOutclock($employees_idNumber)
    {
        $clockOutTime = date('Y-m-d G:i:s');
        $sql = "UPDATE Live_Clock SET clockOut= '$clockOutTime' WHERE employees_idNumber = $employees_idNumber
        ORDER by clockIn DESC
        LIMIT 1";
        $stmt = $this->dbcon->prepare($sql);
        $stmt->bindParam(':clockOut', $clockOutTime, PDO::PARAM_STR);
        $stmt->execute();
    }

//Clock in function
    public function punchInclock($clockinNumber, $employees_idNumber)
    {
        $clockInTime = date('Y-m-d G:i:s');
        $sql = "INSERT INTO Live_Clock (clockIn, employee_clock_num,employees_idNumber)
            VALUES ('$clockInTime', $clockinNumber, $employees_idNumber)";
        $stmt = $this->dbcon->prepare($sql);
        $stmt->bindParam(':clockInTime', $clockInTime, PDO::PARAM_STR);
        $stmt->bindParam(':clockinNumber', $clockinNumber, PDO::PARAM_INT);
        $stmt->bindParam(':Em', $employees_idNumber, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            return true;
        }
        return false;
    }
    public function viewLastPunch($employees_idNumber)
    {
        $sql = "SELECT * FROM Live_Clock WHERE employees_idNumber = $employees_idNumber
        ORDER by clockIn DESC
        LIMIT 1";
        $stmt = $this->dbcon->prepare($sql);
        $stmt->execute(array());
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($res['clockOut'] == null) {
            return $res;
        } else {
            return false;
        }
    }

    public function viewMyScheduleMaker($employees_idNumber){
        $sql="SELECT * 
        FROM Schedule 
        WHERE employees_idNumber = $employees_idNumber";
        $stmt = $this->dbcon->prepare($sql);
        $stmt->execute();
        $res = $stmt->fetchall(PDO::FETCH_ASSOC);
        return $res;
    }


    ///////////-----ADMIN------------///////////////////


   ///---- Viewing ALL details for Team members ----///
   public function viewAllEmployees()
   {
       $sql = "SELECT *
       FROM Employees WHERE is_manager = 0";
       $stmt = $this->dbcon->prepare($sql);
       $stmt->execute();
       $res = $stmt->fetchall(PDO::FETCH_ASSOC);
       return $res;

   }
   //////////----------Create Schedule------//////////////
   public function createSchedule ($employees_idNumber,$department, $startDate_Time,$finishDate_Time,$shiftMsg){
    $sql = "INSERT INTO Schedule (employees_idNumber, Department,ShiftMsg,startDate_Time, finishDate_Time)
    VALUES ( :employees_idNumber, :Department, :ShiftMsg, :startDate_Time,:finishDate_Time)";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->bindParam(':employees_idNumber',  $employees_idNumber, PDO::PARAM_INT);
    $stmt->bindParam(':Department', $department, PDO::PARAM_STR);
    $stmt->bindParam(':ShiftMsg', $shiftMsg, PDO::PARAM_STR);
    $stmt->bindParam(':startDate_Time', $startDate_Time, PDO::PARAM_STR);
    $stmt->bindParam(':finishDate_Time', $finishDate_Time, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() == 1) {
        return true;
    }
    return false;
}


  
    public function upDateEmployee($fN, $lN, $e, $Dep, $phoneN, $clockN,$DOB, $employees_idNumber)
    {
        // if ($this->does_email_exist($e) == true)
        $sql = "UPDATE Employees SET firstName= :firstName, lastName= :lastName, email= :email, department= :department, phone_number= :phone_number, clock_Number= :clock_Number, DOB= :dob WHERE employees_idNumber = '$employees_idNumber'";
        $phoneN = (int)$phoneN;
        $stmt = $this->dbcon->prepare($sql);
        $stmt->bindParam(':firstName', $fN, PDO::PARAM_STR);
        $stmt->bindParam(':lastName', $lN, PDO::PARAM_STR);
        $stmt->bindParam(':email', $e, PDO::PARAM_STR);
        $stmt->bindParam(':department', $Dep, PDO::PARAM_STR);
        $stmt->bindParam(':phone_number', $phoneN, PDO::PARAM_INT);
        $stmt->bindParam(':clock_Number', $clockN, PDO::PARAM_INT);
        $stmt->bindParam(':dob', $DOB, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            return true;
        }
        return false;
    }



    public function addNewEmployee($fN, $lN, $e, $Dep, $pN,$iSM, $clockN, $p, $DOB)
    {

        $sql = "INSERT INTO Employees (firstName,lastName,email, department, phone_number,is_manager,clock_Number,pword,DOB) 
                VALUES (:firstName,:lastName,:email,:department, :phone_number,:is_manager,:clock_Number, :pword,:dob)";

    //// PASSWORD IS HASHED HERE ///////
        $p = password_hash($p,PASSWORD_DEFAULT);
        
        $stmt = $this->dbcon->prepare($sql);
        $stmt->bindParam(':firstName', $fN, PDO::PARAM_STR);
        $stmt->bindParam(':lastName', $lN, PDO::PARAM_STR);
        $stmt->bindParam(':email', $e, PDO::PARAM_STR);
        $stmt->bindParam(':department', $Dep, PDO::PARAM_STR);
        $stmt->bindParam(':phone_number', $pN, PDO::PARAM_INT);
        $stmt->bindParam(':is_manager' , $iSM,PDO::PARAM_INT);
        $stmt->bindParam(':clock_Number', $clockN, PDO::PARAM_INT);
        $stmt->bindParam(':pword', $p, PDO::PARAM_STR);
        $stmt->bindParam(':dob', $DOB, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            return true;
        }
        return false;
    }
    public function deleteEmployee($employees_idNumber)
    {
        $sql = "DELETE FROM Employees WHERE employees_idNumber = $employees_idNumber";
        $stmt = $this->dbcon->prepare($sql);
        $stmt->execute();
        return true;
    }

       ///---- Viewing ALL details for Team members ----///
   public function viewFullSchedule()
   {
       $sql = "SELECT *
       FROM Schedule";
       $stmt = $this->dbcon->prepare($sql);
       $stmt->execute();
       $res = $stmt->fetchall(PDO::FETCH_ASSOC);
       return $res;

   }
   public function viewAvail($employees_idNumber)
    {
        $sql = "SELECT *
        FROM availabilities WHERE employees_idNumber = $employees_idNumber";
        $stmt = $this->dbcon->prepare($sql);
        $stmt->execute(array());
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res;
    }

   
};




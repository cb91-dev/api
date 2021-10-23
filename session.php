<?php

class sessionManager
{
    function __construct()
    {
        $this->counter = 0;
    }

    //Sanitising inbound data
    public function test_input($data)
    {

        // $data = trim($data);
        // $data = stripslashes($data);
        // $data = htmlspecialchars($data);
        return $data;
    }

    public function is_rate_limited()
    {
        $a = time();
        if (isset($_SESSION['last_time_accessed'])) {
            if (($a - $_SESSION['last_time_accessed']) > 1) {
                http_response_code(200);
            } else {
                http_response_code(429);
                die();
            }
        }
        $_SESSION['last_time_accessed'] = $a;
        return true;
    }

    // Function is checking if Session var of 'knokKnock' exists, if it does then add 1 to var counter, if not set counter to 1.
    public function counter()
    {

        // Seeing if Session 'knokKnock' has been set
        if (isset($_SESSION['knokKnock'])) {
            //If it exists asign $counter to 'knokKnock'
            $counter = $_SESSION['knokKnock'];
            // Add 1 to $counter
            $counter++;
            // Return new value to 'knokKnock'
            $_SESSION['knokKnock'] = $counter;
            //Return $counter
            return $counter;
        } else {
            // If 'knokKnock didn't exists, create var that = 1, called $number'
            $number = 1;
            // Asign 'knokKnock' the value of $number
            $_SESSION['knokKnock'] = $number;
            // Asign $counter to session 'knokKnock'
            $counter = $_SESSION['knokKnock'];
            return $counter;
        }
    }
    public function limit_request($counter, $freshMeat)
    {
        // Setting new DateTime to compare $freshmeat
        $testing = new DateTime();
        // Test difference bewteen times
        $interval = $freshMeat->diff($testing);
        // Ask for the seconds from $interval
        $interval->s;
        if ($interval->s < 86400) {
            // Checking if $counter is set
            if (isset($counter)) {
                $end = 1000;
                // Checking if $counter is greater then limit request of 1000
                if ($counter > $end) {
                    http_response_code(429);
                    die();
                    // Checking if $counter is less then limit request of 1000
                } else if ($counter < $end) {
                }
                //$counter wasn't set sorry
            } else {
                http_response_code(429);
            }
            //$interval is greater then 86400 seconds, which is 24 in hours
        } else {
            $new = 0;
            $_SESSION['knokKnock'] = $new;
            $counter = $_SESSION['knokKnock'];
        }
    }







    /// Domain lock web service to a whitelist of referrers
    public function is_corret_origin()
    {
        // Source IP whitelist to restrict access to admin panel
        //HTTP_REFERER header
        $origin = $_SERVER['HTTP_REFERER'];
        // Allowed HTTP_REFERER names
        $allowed_REFERER = [
            "http://localhost:8888",
            "http://localhost:3000"
        ];

        if (in_array($origin, $allowed_REFERER)) {
            header('Access-Control-Allow-Origin: ' . $origin);
        } else {
            http_response_code(403);
            die();
        }
        return true;
    }

    // Validation_email is matching $_POST['email'] and pattern.
    public function validation_email()
    {
        $email_pat = "/[a-z0-9._%+-]+@[a-z0-9.-]+.[a-z]{2,4}$/";
        if (preg_match($email_pat, $_POST['email'])) {
            return true;
        } else {
            return false;
        }
    }
    // Validation_pword is matching $_POST['pword'] and pattern
    public function validation_pword($pword)
    {
        $pword_pat = "/((?=\S*?[A-Z])(?=\S*?[a-z])(?=\S*?[0-9]).{6,})\S$/";
        if (preg_match($pword_pat, $pword)) {
            return true;
        } else {
            return true;
        }
    }
    // Validation_phone_number is matching $_POST['phone_number'] and pattern.
    // $phone_pat is Australian format only 
    public function validation_phone_number($phone_number)
    {
        $phone_pat = "/(^\({0,1}((0|\+61)(2|4|3|7|8)){0,1}\){0,1}(\ |-){0,1}[0-9]{2}(\ |-){0,1}[0-9]{2}(\ |-){0,1}[0-9]{1}(\ |-){0,1}[0-9]{3}$)/";
        if (preg_match($phone_pat, $phone_number)) {
            return true;
        } else {
            return false;
        }
    }
    // Validation_department is matching $_POST['department'] and pattern.
    public function validation_department($department)
    {
        if ($department === 'Front_Counter' || $department === 'Fresh_Produce' || $department === 'Bakery' || $department === 'Deli') {
            return true;
        } else {
            return false;
        }
    }
    // Validation_name is matching $_POST['firstName'],$_POST['lastName'] and pattern.
    public function validation_name($firstName, $lastName)
    {
        $name_pat = "/^[a-zA-Z]{2,66}/";
        if (preg_match($name_pat, $firstName)) {
            if (preg_match($name_pat, $lastName)) {
                return true;
            }
            return false;
        } else {
            return false;
        }
    }
    // Validation_DOB is matching $_POST['DOB']and pattern.
    // Patern format yyyy-mm-dd
    public function validation_DOB($DOB)
    {
        $DOB_pat = "/(?<=\D|^)(?<year>\d{4})(?<sep>[^\w\s])(?<month>1[0-2]|0[1-9])\k<sep>(?<day>0[1-9]|[12][0-9]|(?<=11\k<sep>|[^1][4-9]\k<sep>)30|(?<=1[02]\k<sep>|[^1][13578]\k<sep>)3[01])(?=\D|$)/";
        if (preg_match($DOB_pat, $DOB)) {
            return true;
        } else {
            return false;
        }
    }
    // Validation_days is matching $_POST and patterns.
    public function validation_days($days)

    {
        $x = "^(?:Yes|No)$";
        if (in_array($x, $days)) {
            return true;
        } else {
            return false;
        }
    }


    // Validation_Clockon is matching $_POST['clockNumber'] and patterns.
    public function validation_Clockon($value)
    {

        $clock_pat = "/^[0-9]{4}$/";
        if (preg_match($clock_pat, $value)) {
            return true;
        } else {
            return false;
        }
    }



    // Checking that $_POST is a valid email

    public function emailCheck($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }

    /// Checking Create Schulde is good and correct

    public function checkSchedule ($id,$dep,$dF,$dN,$tF,$tT){

    }
}

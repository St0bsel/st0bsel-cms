<?php
/*
____________________________________________________________________________________
INFORMATION:
Created on:     23.11.16
Created by:     Tobias Zweifel
Last changes:
Last edited by:
Function:       This script contains functions for making a login function on the
                website posible.
                Script will be called by ajax with the needed informations to makes
                a user loged in or loged out. This script is also used to check if
                a user is already loged in and you can register new users over
                this script. TL;dr everthing about users
                We will work here with PHP secure sessions and some layers of
                encrypting.

Remarks:        Security: we do what we can, but it's very complicated

This code contaions parts of the following Tutorial:
http://www.wikihow.com/Create-a-Secure-Session-Management-System-in-PHP-and-MySQL
____________________________________________________________________________________
*/
//include file with database informations
  require 'sql_login.php';

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  //this function starts a PHP sec Session on the server.
  function sec_session_start() {
    $session_name = 'sec_session_id';
    $secure = false;
    //parameter for denying JS to accessing session id
    $httponly = true;
    //forces the session to only use cookies
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
        header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
        exit();
    }
    //get Cookie-Parameter.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"],
        $cookieParams["path"],
        $cookieParams["domain"],
        $secure,
        $httponly);
    //set the session name
    session_name($session_name);
    session_start();            //start session
    session_regenerate_id();    // renew the session and delet the old
}

  /*
  We use here the PHP echo function so JS if something went wrong

  echo
  1 = true
  2 = user dont exist
  3 = password incorect
  4 = error
  */

  //This function logs a user in the first time
  function login(){
    //get infors from client over POST
    $username = $_POST['username'];
    $password = $_POST['password'];

    //remove backslashes from string #mysqlinjection
    $username = stripslashes($username);
    $password = stripslashes($password);
    //hash password
    $password = hash('sha512', $password);
    //check some diferent thigs if userer exists and if everthing is ok
    //look on the echo parameters
    $check = mysqli_query($GLOBALS['sql_connection'], "SELECT UserID, Username, Userpassword FROM users WHERE Username='".$username."'");
    $rowcount = mysqli_num_rows($check);

    if ($rowcount == 1) {
      $db_data = mysqli_fetch_all($check, MYSQL_NUM);
      $db_userid = $db_data[0][0];
      $db_user = $db_data[0][1];
      $db_password = $db_data[0][2];
      $db_user = stripslashes($db_user);
      $db_password = stripslashes($db_password);
      if ($db_password != $password) {
        echo 3;
        return 3;
      }
      else {
        sec_session_start();
        $_SESSION['userid'] = $db_userid;
        $_SESSION['username'] = $db_user;
        $_SESSION['password'] = $db_password;
        echo 1;
        return 1;
      }
    }
    else {
      echo 2;
      return 2;
    }

  }

  //check if user is already loged in
  function checklogin(){
    //start a sessoion and check if threre are already some values saved
    sec_session_start();
    if (isset($_SESSION['userid'],$_SESSION['username'], $_SESSION['password'])) {
      $userid = $_SESSION['userid'];
      $username = $_SESSION['username'];
      $password = $_SESSION['password'];
      //check if stored infos in session are correct
      $check = mysqli_query($GLOBALS['sql_connection'], "SELECT UserID, Username, Userpassword FROM users WHERE UserID='".$userid."'");
      $rowcount = mysqli_num_rows($check);
      if ($rowcount == 1) {
        $db_data = mysqli_fetch_all($check, MYSQL_NUM);
        $db_userid = $db_data[0][0];
        $db_user = $db_data[0][1];
        $db_password = $db_data[0][2];
        $db_user = stripslashes($db_user);
        $db_password = stripslashes($db_password);
        if ($db_password != $password) {
          echo 3;
          return 3;
        }
        else {
          echo 1;
          return 1;
        }
      }
      else {
        echo 2;
        return 2;
      }
    }
    else {
      echo "4 session not set";
      return 4;
    }
  }

  //logout user = clear session parameters and delet it
  function logout(){
    sec_session_start();
    $_SESSION = array();
    session_destroy();
    echo 1;
    return 1;
  }

  //register new user
  function register(){
    //get values from client
    $username = $_POST['username'];
    $password = $_POST['password'];
    $usermail = $_POST['usermail'];
    //check if userallready exists
    $check = mysqli_query($GLOBALS['sql_connection'], "SELECT username FROM users WHERE username = '$username'");
    $rowcount = mysqli_num_rows($check);
    if ($rowcount == 1) {
      echo "user exists";
      return 4;
    }

    //add some backslashes #mysqlinjection
    $password = addslashes(hash('sha512',$password));
    $username = addslashes($username);
    $usermail = addslashes($usermail);
    // now we insert it into the database
    $currentdate = date('Y-m-d H:i:s');
    $insert = "INSERT INTO users (Username, Userpassword, Usermail, registerton) VALUES ('$username', '$password', '$usermail', '$currentdate')";
    $add_member = mysqli_query($GLOBALS['sql_connection'],$insert);
    if (login() == 1) {
      echo 1;
      return 1;
    }
    else {
      echo "login error";
      return 4;
    }
  }

  //change users password
  function changepassword(){
    sec_session_start();
    $currentpassword = addslashes(hash('sha512',$_POST['password']));
    $currentuser = addslashes($_POST['username']);
    $currentuser2 = addslashes($_SESSION['username']);
    $currentpassword2 = $_SESSION['password'];
    $newpassword = addslashes(hash('sha512',$_POST['newpassword']));
    if (strcasecmp($currentuser, $currentuser2) != 0) {
      echo "4 usererror";
      return 4;
    }
    else if ($currentpassword != $currentpassword2) {
      echo "4 passworderror";
      return 4;
    }
    else {
      $insert = "UPDATE users SET Userpassword='".$newpassword."' WHERE Username='".$currentuser."'";
      $add_member = mysqli_query($GLOBALS['sql_connection'],$insert);
      logout();
      echo 1;
      return 1;
    }
  }

  //this function returns the infos about the user in a JSON string
  function getuserdata(){
    sec_session_start();
    $userid = $_SESSION['userid'];
    $check = mysqli_query($GLOBALS['sql_connection'], "SELECT Usermail FROM users WHERE userid='$userid'");
    echo json_encode(mysqli_fetch_all($check, MYSQL_NUM));
  }

  //here starts everything
  //get the functionname from the client and run the required function
  if(function_exists($_POST['f'])) {
    //check all POST for mysql_injection
    $echovalue = $_POST['f']();
  }
 ?>

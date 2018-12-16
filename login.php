<!-- Week 14 Assignment
     Dec 6, 2018
     Kevin Leehan
     login.php
     check if the username entered exists in the database 
     compares the given password to what is on file.
-->

<?php
  require_once "pdo.php";
  session_start();

  if ( isset($_POST["logIn"]) )
  {

    unset($_SESSION["account"]); // Logout current users

    $_SESSION["account"] = $_POST["account"];
    $_SESSION["pw"] = $_POST["pw"];

    $sql = "SELECT UserID, `Email Address`, Password FROM users WHERE `Email Address` = :name";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
      ':name' => $_SESSION['account']));

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($stmt->rowCount() == 1){
      foreach($result as $row) {
        // Check if given password matches password in database
        if ($_SESSION['pw'] != $row['Password']) {
          $_SESSION["error"] = "Incorrect password.";
          $_SESSION['account'] = null;
          $_SESSION['pw'] = null;
          header( 'Location: login.php' );
          return;
        }
        $_SESSION["success"] = "Logged in.";
        $_SESSION["userId"] = $row['UserID'];
        header( 'Location: twitter.php' );
        return;
      }

    }else{
      // no SQL result returned on given email address
      $_SESSION["error"] = "Account not found.";
      $_SESSION['account'] = null;
      header( 'Location: login.php' );
      return;
    }
  }


?>
<html>
<head>
  <title>Login Page</title>
  <link  type="text/css" rel="stylesheet" href="style.css">
</head>
  <div id="main">
    <body syle="font-family: sans-serif;">
    <h1> Please Log In </h1>
    <?php
      if ( isset($_SESSION["error"]) ){
        echo('<p style="color:red">'.$_SESSION["error"]."</p>\n");
        unset($_SESSION["error"]);
      }
    ?>
    <form method="post">
    <p>Email: <input type="text" name="account" value="" id="id_act"></p>
    <p>Password: <input type="text" name="pw" value="" id="id_pw"></p>

    <p><input type="submit" onclick="return doValidate();" name=logIn value="Log In">
    </form>
    <a href="twitter.php" class="button">Cancel</a>
    <a href="newUser.php" class="button">Create new account</a>

    <script>
    function doValidate() {
        console.log('Validating...');
        try {
          var act = document.getElementById('id_act').value;
          var pw = document.getElementById('id_pw').value;
          console.log("Validating password="+pw);

          if (pw == null || pw == "" || act == null || act == "") {
            alert("Both fields must be filled out");
            return false;
          }
          console.log("Validating Email="+act);
          if(!validateEmail(act)){
            alert("Please provide a valid email address. The email addres must contain @ ");
            return false;
          }
          return true;
        } catch(e) {
          return false;
        }
        return false;
      }
      //source: http://form.guide/best-practices/validate-email-address-using-javascript.html
    function validateEmail(email){
      var re = /^(?:[a-z0-9!#$%&amp;'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&amp;'*+/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])$/;
      return re.test(email);
    }
    </script>
    </body>
  </div>
</html>

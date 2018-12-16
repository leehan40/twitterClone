<!-- Week 14 Assignment
     Dec 6, 2018
     Kevin Leehan
     newUser.php
     allows the user to create a new account inorder to post tweets and likes
     checks if email address is already in the database
-->

<?php
  require_once "pdo.php";
  session_start();
  if(isset($_POST['newUser']) && !($_POST['newEmail'])==null && !($_POST['newPw'])==null)
  {
      $_SESSION["newEmail"] = $_POST["newEmail"];
      $_SESSION["newPw"] = $_POST["newPw"];

      //Check if Email address is already in database
      $sql = "SELECT UserID, `Email Address`, Password FROM users WHERE `Email Address` = :name";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(
        ':name' => $_SESSION['newEmail']));

      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

      if($stmt->rowCount() == 1){
        $_SESSION["error"] = "Account already exists.";
        $_SESSION['newEmail'] = null;
        $_SESSION['newPw'] = null;
        header( 'Location: newUser.php' );
        return;
      }
      else {
        $sql = "INSERT INTO users (`Email Address`, Password) VALUES (:Email, :Pw)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
          ':Email' => $_SESSION['newEmail'],
          ':Pw' => $_SESSION['newPw']));
          header( 'Location: login.php' );
      }
  }

?>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <title>New User Signup</title>
    <link  type="text/css" rel="stylesheet" href="style.css">
  </head>
  <body>
    <div id = "main">
      <form method="post">
        <table>
          <H2>Create your account</h2>
            <?php
              if ( isset($_SESSION["error"]) ){
                echo('<p style="color:red">'.$_SESSION["error"]."</p>\n");
                unset($_SESSION["error"]);
              }
              if ( isset($_SESSION["success"]) ){
                echo('<p style="color:green">'.$_SESSION["success"]."</p>\n");
                unset($_SESSION["success"]);
              }
            ?>
          <tr>
            <td>Email: </td>
            <td><input type="text" name="newEmail" size="35" id="id_act"></td>
          </tr>
          <tr>
            <td>Password: </td>
            <td><input type="text" name="newPw" size="35" id="id_pw"></td>
          </tr>
          <tr>
            <td><a href="twitter.php" class="button">Cancel</a></td>
            <td><input type="submit" onclick="return doValidate();" value="Create new user" name="newUser"/></td>
            </tr>
        </table>
      </form>
    </div>

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
            alert("Please provide a valid email address. The email address must contain @ ");
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
</html>

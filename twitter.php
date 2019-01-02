<!-- Week 14 Assignment
     Dec 6, 2018
     Kevin Leehan
     twitter.php
     Main page of this twitter Clone
     Displays all sumbitted tweets
     Allows logged in Users to post tweets and like tweets
-->

<?php
  session_start();
  require_once "pdo.php";
?>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <title>Twitter Clone</title>
    <link  type="text/css" rel="stylesheet" href="style.css">
  </head>
  <body>
    <div id="main">
      <div id="header">
        <?php
        if ( isset($_SESSION["success"]) ){
          echo('<p style="color:green">'.$_SESSION["success"]."</p>\n");
          unset($_SESSION["success"]);
        }

        //Check login status
        if (! isset($_SESSION["account"]) ){
          echo('<a href="login.php" class="button">Log In</a>');
        }
        else{
          echo('<p>Welcome, '.$_SESSION["account"].' ' );
          echo('<a href="logout.php" class="button">Log Out</a>');
        }

        //Check AddTweet
        if (isset($_POST['NewTweetButton']) && $_POST['newTweet']!=null ){
          $sql = "INSERT INTO Messages(userID, message) VALUES (:userID, :message)";
          $stmt = $pdo->prepare($sql);
          $stmt->execute(array(
            ':userID' => $_SESSION['userId'],
            ':message' => $_POST['newTweet']));
            echo('<p style="color:green">'.$_SESSION['userId']."</p>\n");
            echo('<p style="color:green">'.$_POST['newTweet']."</p>\n");
            header( 'Location: twitter.php' );
          }
          ?>
      </div>
      <div id = "tweetStream">

        <div id = "addTweet">
        <form method="post">
          <input type="hidden" name="userID"  value="<?php echo $_SESSION['account'];  ?>">
          <table>
            <H2>Add a tweet</h2>
            <tr>
              <td><input type="text" name="newTweet" size="50"></td>
            </tr>
            <tr>
              <td><input type="submit" value="Add Tweet" name="NewTweetButton"/></td>
            </tr>
          </table>
        </form>
      </div>

        <div id="tweets">
          <H2>Tweets</H2>

            <?php
            $stmt = $pdo->query("SELECT `Users`.`Email Address` AS Email,
                                      `Messages`.`Message` AS Message,
                                      `Messages`.`MessageID`,
                                      (SELECT Count(*)
                                        FROM `MessagesLikes`
                                        WHERE `Messages`.`MessageID` = `MessageID`
                                        ) AS Likes
                              FROM `Messages`
                              LEFT JOIN `Users` ON `Users`.`UserID` = `Messages`.`UserID`
                              ORDER BY `Messages`.`MessageID` DESC;");
            while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
              echo('<form method="post" action="likeButton.php">');
                echo "<tr><td>";
              ?><div id=tweetsUser><?php
                echo($row['Email'] . " tweeted:");
              ?><div id="tweetsMessage"><?php
                echo($row['Message']);
              ?></div><?php
              ?><div id="tweetsLikes"><?php
                echo "Likes ";
                echo($row['Likes']);

              echo('<input type="hidden" name="MessageID" value="'.$row['MessageID'].'">'."\n");
              echo('<input type ="submit" value="Like" name="LikeButton" id="likeTweet">');
                ?></div><?php
              echo("\n</form>\n");
            }
            ?></div><?php
            //Causes Add tweet and like button to disapper if user not logged in
            if(!isset($_SESSION["account"]))
            { ?>
              <style type="text/css">#addTweet{
                 display:none;
               }</style>
              <style type="text/css">#likeTweet{
                 display:none;
                 }</style>
             <?php
             }
             ?>
        </div>
      </div>
    </div>
  </body>
</html>

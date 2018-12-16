<!-- Week 14 Assignment
     Dec 6, 2018
     Kevin Leehan
     likeButton.php
     checks if the the current logged in user has or has not already
     liked the tweet in which the user clicked like for.
     It will increment or decrement the likes for the tweet by 1 depending
     on the situation
-->

<?php
  session_start();
  require_once "pdo.php";

    $sql = "SELECT * FROM messageslikes WHERE MessageID = :messageID AND UserID = :userID";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
      ':messageID' => $_POST['MessageID'],
      ':userID' => $_SESSION['userId']));

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $temp = count($result);

    // like tweet
    if($temp < 1){
        $sql = "INSERT INTO messageslikes(MessageID, UserID) VALUES (:messageID, :userID)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
          ':messageID' => $_POST['MessageID'],
          ':userID' => $_SESSION['userId']));
            header( 'Location:twitter.php' );
      }

      // unlike tweet
      if($temp == 1){
        $sql = "DELETE FROM messageslikes WHERE MessageID = :messageID AND UserID = :userID";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
          ':messageID' => $_POST['MessageID'],
          ':userID' => $_SESSION['userId']));
            header( 'Location:twitter.php' );
      }
?>

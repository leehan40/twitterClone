<?php
  $pdo=new PDO('mysql:host=mytwitterclonedb.cpubu2ymknpg.us-west-2.rds.amazonaws.com;port=3306;dbname=myTwitterClone',
                'admin','twitterclone');
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 ?>

<?php
/**
 * Created for crosslink-viewer.
 * User: chuangchuangzhang
 * Date: 2018/2/12
 * Time: 21:28
 *
 * Desc:
 */

include('./uploadsConnectionString.php');

//if ($_FILES["upfile"]["error"] > 0) {
//    echo "Error: " . $_FILES["upfile"]["error"] . "<br />";
//} else {
//    echo "Upload: " . $_FILES["upfile"]["name"] . "<br />";
//    echo "Type: " . $_FILES["upfile"]["type"] . "<br />";
//    echo "Size: " . ($_FILES["upfile"]["size"] / 1024) . " Kb<br />";
//    echo "Stored in: " . $_FILES["upfile"]["tmp_name"];
//}

if (empty($_FILES['upfile']['tmp_name'])) {
    echo '<h3>No CSV file uploaded.</h3>';
}
else {
    //randomString
    $rand = sha1(uniqid(mt_rand(), true));
    //echo $rand;
    $linkData = addslashes(file_get_contents($_FILES['upfile']['tmp_name']));
    //echo $linkData;
    $fileName =  $_FILES["upfile"]["name"];
    //echo $fileName;
    if (isset($_FILES['upfasta']['name']) && $_FILES['upfasta']['name'] != '') {
        $fastaData = addslashes(file_get_contents($_FILES['upfasta']['tmp_name']));
    } else {
        $fastaData = '';
    }

    //echo $fastaData;
    if (isset($_FILES['upannot']['name']) && $_FILES['upannot']['name'] != '') {
        $annotData = addslashes(file_get_contents($_FILES['upannot']['tmp_name']));
    } else {
        $annotData = '';
    }

    //echo $annotData;

    $dbconn = new mysqli($config['host'], $config['user'], $config['password'], $config['dbname']);

    if ($dbconn->connect_error) {
        die('Connect Error (' . $dbconn->connect_errno . ') '
            . $dbconn->connect_error);
    }
    /* If we have to retrieve large amount of data we use MYSQLI_USE_RESULT */
    if ($result = $dbconn->query("INSERT INTO upload (rand, links, fileName, fasta, annot) "
        . "VALUES ('".$rand."','".$linkData."','".$fileName."','".$fastaData."','".$annotData."')", MYSQLI_USE_RESULT)) {

        // $result->free();
    }
    $dbconn->close();
    /* $dbconn = pg_connect($connectionString)
    or die('Could not connect: ' . pg_last_error());
    $query = "INSERT INTO upload (rand, links, fileName, fasta, annot) "
        . "VALUES ('".$rand."','".$linkData."','".$fileName."','".$fastaData."','".$annotData."');";
    //echo $query;
    $result = pg_query($query) or die('Query failed: ' . pg_last_error());
    // Free resultset
    pg_free_result($result);
    // Closing connection
    pg_close($dbconn);
    //redirect to page with unique url */
    header('Location: ./uploaded.php?uid='.$rand);
}

<?php

try{
    include "Lab1dbconfig.php";

    $id = isset($_GET['id'])? $_GET['id'] : die("ERROR: missing BorrowID parameter.");
    //delete query
    $borrowquery = "DELETE FROM borrowings WHERE BorrowID = ?";
    $borrowstmt = $conn->prepare($borrowquery);
    $borrowstmt->bindparam(1, $id);

    if ($borrowstmt->execute()){
        header("location: Index.php");
    }
    
    $id = isset($_GET['id'])? $_GET['id'] : die("ERROR: missing BorrowID parameter.");
    //delete query
    $itemquery = "DELETE FROM items WHERE ItemID = ?";
    $itemstmt = $conn->prepare($itemquery);
    $itemstmt->bindparam(1, $id);

    if ($itemstmt->execute()){
        header("location: Index.php");
    }

    $id = isset($_GET['id'])? $_GET['id'] : die("ERROR: missing BorrowID parameter.");
    //delete query
    $userquery = "DELETE FROM users WHERE UserID = ?";
    $userstmt = $conn->prepare($userquery);
    $userstmt->bindparam(1, $id);

    if ($userstmt->execute()){
        header("location: Index.php");
    }

}

catch(PDOException $e){
    die("ERROR: Could not delete record: ". $e->getMessage());
}


?>
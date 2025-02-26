<?php
    try {
        if(empty($errors)) {
            include "Lab1dbconfig.php";
            
            // Start transaction
            $conn->beginTransaction();
    
            // Insert into users table
            $id = isset($_GET['id'])? $_GET['id'] : die("ERROR: record ID not found.");
            $userQuery = "SELECT UserName, Email FROM users WHERE UserID = ? LIMIT 0,1";
            $userStmt = $conn->prepare($userQuery);
            $userStmt->bindParam(1, $id);
            $userStmt->execute();
            
            $userrow = $userStmt->fetch(PDO::FETCH_ASSOC);
            $editUserName = $userrow['UserName'];
            $editEmail = $userrow['Email'];

            // Insert into items table
            $itemQuery = "SELECT ItemName FROM items WHERE ItemID =? LIMIT 0,1";
            $itemStmt = $conn->prepare($itemQuery);
            $itemStmt->bindParam(1, $id);
            $itemStmt->execute();
            
            $itemrow = $itemStmt->fetch(PDO::FETCH_ASSOC);
            $editItemName = $itemrow['ItemName'];
    
            // Insert into borrowings table
            $borrowQuery = 
            "SELECT UserID, ItemID, BorrowDate, DueDate, Status, UsageLocation FROM borrowings WHERE BorrowID =? LIMIT 0,1";
            $borrowStmt = $conn->prepare($borrowQuery);
            $borrowStmt->bindParam(1, $id);
            $borrowStmt->execute();

            $borrowrow = $borrowStmt->fetch(PDO::FETCH_ASSOC);
            $editUserId = $borrowrow['UserID'];
            $editItemId = $borrowrow['ItemID'];
            $editBorrowDate = $borrowrow['BorrowDate'];
            $editDueDate = $borrowrow['DueDate'];
            $editStatus = $borrowrow['Status'];
            $editUsage = $borrowrow['UsageLocation'];

    
            // Commit the transaction
            $conn->commit();
    
            $msg = "<div class='alert alert-success'><strong>Records were saved successfully</strong></div>";
        }
    } catch(PDOException $e) {
        // Rollback the transaction if there's an error
        $conn->rollBack();
        $msg = "<div class='alert alert-danger'><strong>ERROR: ". $e->getMessage(). "</strong></div>";
    }

$msg = "";
function sanitizeInput($data) {
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = trim($data);
    return $data;
}

function validateInput($data, $pattern) {
    return preg_match($pattern, $data);
}

$pattern = [
    'UserName' => '/^[a-zA-Z\s]+$/',
    'ItemName' => '/^[a-zA-Z\s]+$/',
    'Email' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
    'Usage' => '/^[a-zA-Z\s]+$/',
    'BorrowDate' => '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/',
    'DueDate' => '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/',
    'Status' => '/^(Borrowed|Returned|Overdue)$/', // /^(Borrowed|Returned)$/ for exact match only

];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $UserName = sanitizeInput($_POST['UserName']);
    $Email = sanitizeInput($_POST['Email']);
    $Usage = sanitizeInput($_POST['Usage']);
    $ItemName = sanitizeInput($_POST['ItemName']);
    $BorrowDate = sanitizeInput($_POST['BorrowDate']);
    $DueDate = sanitizeInput($_POST['DueDate']);
    $Status = sanitizeInput($_POST['Status']);


    $errors = [];
    
    if (!validateInput($UserName, $pattern['UserName'])) {
        $errors['UserName'] = "Invalid Customer Name 25 characters";
    }

    if (!validateInput($Email, $pattern['Email'])) {
        $errors['Email'] = "Invalid Email";
    }
    
    if (!validateInput($Usage, $pattern['Usage'])) {
        $errors['Usage'] = "Invalid Usage";
    }
    
    if (!validateInput($ItemName, $pattern['ItemName'])) {
        $errors['ItemName'] = "Invalid Item Name 50 characters";
    }
    
    if (!validateInput($BorrowDate, $pattern['BorrowDate'])) {
        $errors['BorrowDate'] = "Invalid Borrow Date";
    }
    
    if (!validateInput($DueDate, $pattern['DueDate'])) {
        $errors['DueDate'] = "Invalid Due Date";
    }
    if (!validateInput($Status, $pattern['Status'])) {
        $errors['Status'] = "Invalid Status Borrowed/Returned/Overdue";
    }

    try {
        if(empty($errors)) {
            include "Lab1dbconfig.php";
            
            // Start transaction
            $conn->beginTransaction();
    
            // Update users table
            $userQuery = "UPDATE users SET UserName = ?, Email = ? WHERE UserID = ?";
            $userStmt = $conn->prepare($userQuery);
            $userStmt->bindParam(1, $UserName);
            $userStmt->bindParam(2, $Email);
            $userStmt->bindParam(3, $editUserId);
            $userStmt->execute();
            
            // Update items table
            $itemQuery = "UPDATE items SET ItemName = ? WHERE ItemID = ?";
            $itemStmt = $conn->prepare($itemQuery);
            $itemStmt->bindParam(1, $ItemName);
            $itemStmt->bindParam(2, $editItemId);
            $itemStmt->execute();
    
            // Update borrowings table
            $borrowQuery = "UPDATE borrowings SET BorrowDate = ?, DueDate = ?, Status = ?, UsageLocation = ? WHERE BorrowID = ?";
            $borrowStmt = $conn->prepare($borrowQuery);
            $borrowStmt->bindParam(1, $BorrowDate);
            $borrowStmt->bindParam(2, $DueDate);
            $borrowStmt->bindParam(3, $Status);
            $borrowStmt->bindParam(4, $Usage);
            $borrowStmt->bindParam(5, $id);
            $borrowStmt->execute();
    
            // Commit the transaction
            $conn->commit();
    
            $msg = "<div class='alert alert-success'><strong>Records were updated successfully</strong></div>";
        }
    } catch(PDOException $e) {
        // Rollback the transaction if there's an error
        $conn->rollBack();
        $msg = "<div class='alert alert-danger'><strong>ERROR: ". $e->getMessage(). "</strong></div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Buttons.css?v=<?php echo time();?>">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>Document</title>
</head>
<body>

<div class="container mt-5 mb-5 d-flex justify-content-center">
          <div class="card w-50">
        <div class="card-body">
            <?php echo $msg;?>
          <form action="#" method="POST">

          <!-- Customer Name -->
            <div class="form-group mt-2">
                <label for="UserName">Customer Name</label>
                <input type="text" class="form-control" id="UserName" name="UserName" placeholder="Enter Customer Name" maxlength="25" value="<?php echo $editUserName;?>">
                <span class="text-danger"><?php echo $errors['UserName']?? '';?></span>
            </div>

            <!-- Email -->
             <div class="form-group mt-2">
                <label for="Email">Email</label>
                <input type="email" class="form-control" id="Email" name="Email" placeholder="Enter Email" value="<?php echo $editEmail;?>">
                <span class="text-danger"><?php echo $errors['Email']?? '';?></span>
            </div>

            <!-- Item Name -->
            <div class="form-group mt-2">
                <label for="ItemName">Item Name</label>
                <input type="text" class="form-control" id="ItemName" name="ItemName" placeholder="Enter Item Name" maxlength="50" value="<?php echo $editItemName;?>">
                <span class="text-danger"><?php echo $errors['ItemName']?? '';?></span>
            </div>

            <!-- Usage -->
             <div class="form-group mt-2">
                <label for="Usage">Usage</label>
                <input type="text" class="form-control" id="Usage" name="Usage" placeholder="Enter Usage" value="<?php echo $editUsage;?>">
                <span class="text-danger"><?php echo $errors['Usage']?? '';?></span>
            </div>

            <!-- Borrow Date -->
            <div class="form-group mt-2">
                <label for="BorrowDate">Borrow Date</label>
                <input type="datetime-local" class="form-control" id="BorrowDate" name="BorrowDate" value="<?php echo $editBorrowDate;?>">
                <span class="text-danger"><?php echo $errors['BorrowDate']?? '';?></span>
            </div>

            <!-- Due Date -->
            <div class="form-group mt-2">
                <label for="DueDate">Due Date</label>
                <input type="datetime-local" class="form-control" id="DueDate" name="DueDate" value="<?php echo $editDueDate;?>">
                <span class="text-danger"><?php echo $errors['DueDate']?? '';?></span>
            </div>
            <!-- Status -->
            <div class="form-group mt-2">
                <label for="Status">Status</label>
                <input type="text" class="form-control" id="Status" name="Status" placeholder="Enter Status" value="<?php echo $editStatus;?>">
                <span class="text-danger"><?php echo $errors['Status']?? '';?></span>
            </div>

            <!-- Submit Button -->
            <div class="form-group mt-3 d-flex justify-content-center">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="Index.php" class="btn1 ms-3">Cancel</a>
            </div>
</body>
</html>


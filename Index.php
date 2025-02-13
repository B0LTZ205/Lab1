<?php
include "Lab1dbconfig.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Borrowed items</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container mt-3">
  <h2>Borrow Information</h2>
  <p></p>            
  <table class="table table-striped">
    <thead class="table-dark">
      <tr>
        <th>BorrowID</th>
        <th>UserName</th>
        <th>ItemName</th>
        <th>BorrowDate</th>
        <th>DueDate</th>
        <th>Status</th>
      </tr>
    </thead>
    
  <?php 
  // Include your database query here using the $conn variable from Lab1dbconfig.php.
  include "Lab1dbconfig.php";

  //select data from the database and display it in the table.
  $query = "SELECT BorrowID, UserName, ItemName, BorrowDate, DueDate, Status FROM borrowings, users, items
  WHERE borrowings.UserID = users.UserID AND borrowings.ItemID = items.ItemID";

  // Prepare the statement
  $stmt = $conn->prepare($query);

  // Execute the statement
  $stmt->execute();

  // Fetch all the rows as an associative array
  while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {

    extract($rows);

    echo "<tr>";
    echo "<td>".$BorrowID."</td>";
    echo "<td>".$UserName."</td>";
    echo "<td>".$ItemName."</td>";
    echo "<td>".$BorrowDate."</td>";
    echo "<td>".$DueDate."</td>";
    echo "<td>".$Status."</td>";
    echo "</tr>"; 

  };

  
  
  
  
  
  
  ?>
  </table>
</div>

</body>
</html>

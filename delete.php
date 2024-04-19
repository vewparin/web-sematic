<?php
include 'database.php';

// Check if ID parameter is set
if (isset($_POST["id"])) {
    $id = $_POST["id"];
    
    // Delete the row with the specified ID
    $sql = "DELETE FROM reviews1 WHERE id = $id";
    $result = pg_query($sql);
    
    if ($result) {
        // Redirect back to the reviews page after deletion
        header("Location: reviews.php");
        exit();
    } else {
        echo "Error: Failed to delete record.";
    }
} else {
    echo "Error: No ID specified.";
}
?>

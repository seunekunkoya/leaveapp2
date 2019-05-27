<?php
// Initialize the session
//session_start();
 
/*Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["staffid"]) && $_SESSION["staffid"] === true){
    header("location: welcome.php");
    exit;
}
*/
 
// Include config file
// require_once "config/database.php";
// include 'leavefunction.php';
 
include "include/config.php";
// Define variables and initialize with empty values
$userid = "";
$userid_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $userid_err = "Please enter username.";
    } else{
        $userid = trim($_POST["username"]);
    }
    
    // Validate credentials
    if(empty($userid_err)){
        if ($lvobj->login($userid)) {
            // Redirect if logged in successfully
            $lvobj->redirect("leavedashboard.php?id=".base64_encode($userid)."");
        // Prepare a select statement
        // $sql = "SELECT * FROM stafflst WHERE staffid = :userid";
        
        // try{
        //     if($stmt = $con->prepare($sql)){
        //     // Bind variables to the prepared statement as parameters
        //     $stmt->bindParam(":userid", $param_username, PDO::PARAM_STR);
            
        //     // Set parameters
        //     $param_username = trim($_POST["username"]);
            
        //     // Attempt to execute the prepared statement
        //     if($stmt->execute()){
        //         // Check if username exists, if yes then verify password
        //         if($stmt->rowCount() == 1){
        //             if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        //                 $staffdetails = $row;
        //                 $_SESSION['staffdetails'] = $staffdetails;
        //                 //print_r($staffdetails);

        //                 header("location: leavedashboard.php?id=".base64_encode($userid));
                        
        //                 }//end of row if statement
        //         } 
        //         else
        //         {
        //             // Display an error message if username doesn't exist
        //             $userid_err = "No account found with that userID.";
        //         }
        //     } 

        //     else{
        //         echo "Oops! Something went wrong. Please try again later.";
        //     }
        // }
        }//end of try
        //catch(PDOException $e){ $e->getMessage();}
        
        
        // Close statement
        //unset($stmt);
    }else{ $userid_err = "No account found with that userID."; }
    
    // Close connection
    //unset($pdo);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($userid_err)) ? 'has-error' : ''; ?>">
                <label>User ID</label>
                <input type="text" name="username" class="form-control" value="<?php //echo $username; ?>">
                <span class="help-block"><?php echo $userid_err; ?></span>
            </div>    
            
            <div class="form-group">
                <input type="submit" class="btn btn-default" value="Login">
            </div>
            
        </form>
    </div>
</body>
</html>
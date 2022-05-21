<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="icon.png" type="image/x-icon" />

    <title>Shourya Srivastava</title>
</head>
<?php
require_once "pdo.php";
if( isset($_POST['who']) && isset($_POST['pass'])){
    if($_POST['who']=="" || $_POST['pass']==""){
        echo '<p style = "color: red"> Email User name and Password are required </p>';
    }
    else if(!strpos($_POST['who'],'@')) {
        echo '<p style = "color: red"> Email must have an at-sign (@) </p>';
    }
    else {
        $sql = "SELECT name FROM user WHERE email = :em AND password = :pass";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':em' => $_POST['who'],
            ':pass' => $_POST['pass'] 
        ));

        $row = $stmt->fetch(PDO:: FETCH_ASSOC);
        
        // error log
        
        if(!$row){
            $hash  = hash('sha256', $_POST['pass']);
            error_log("Login fail ".$_POST['who']."$hash");
            echo '<p style = "color: red"> Incorrect Password </p>';
            
        }
        else {
            error_log("Login Success ".$_POST['who']);
            echo '<p style = "color: green"> Login Successful </p>';
            header("Location: autos.php?name=".urlencode($_POST['who']));
        }

    }

}
?>
<body>
<h1>Please Log In</h1>
<form method="POST">
<label for="name">User Name</label>
<input type="text" name="who" id="name"><br/>
<label for="id_1723">Password</label>
<input type="text" name="pass" id="id_1723"><br/>
<input type="submit" value="Log In">

</form>
<?php
        require_once "pdo.php";
        if(isset($_GET['name'])){
            echo "<h1> Tracking Autos for ".$_GET['name']."</h1>";
        }
        // else {
        //     die("Name parameter missing");
        // }

        if(isset($_POST['logout'])) {
            header('Location: index.php');
        } else {
         
            if(isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage'])) {

                if ($_POST['make'] == "") {
                    echo "<p style='color: red'>Make is required</p>";
                } elseif (is_numeric($_POST['year']) && is_numeric($_POST['mileage'])) {
                        $stmt = $pdo->prepare('INSERT INTO autos
                            (make, year, mileage) VALUES ( :mk, :yr, :mi)');
                            
                        $stmt->execute(array(
                            ':mk' => $_POST['make'],
                            ':yr' => $_POST['year'],
                            ':mi' => $_POST['mileage'])
                        );

                        echo "<p style='color: green'>Record inserted</p>";
                } else {
                    echo "<p style='color: red'>Mileage and year must be numeric</p>";   
                }
                
            }   
        }
    ?>
<div class="container">
<form method="post">
<p>Make:
<input type="text" name="make" size="60"/></p>
<p>Year:
<input type="text" name="year"/></p>
<p>Mileage:
<input type="text" name="mileage"/></p>
<button>Add</button>
<input type="submit" value="Add" name="Add" />
    <input type="submit" value="logout" name="logout" />
        
</form>

<h2>Automobiles</h2>
    <ul>
        <?php
            
            $statement = $pdo->query("SELECT auto_id, make, year, mileage FROM autos");
            
            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                echo "<li> ";
                echo $row['year']." ";
                echo htmlentities($row['make'])." / ";
                echo $row['mileage'];
                echo "</li>";
            }
        ?>
    </ul>
</body>

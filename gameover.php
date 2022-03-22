<?php
// starts session
session_start();

$name = $_POST['name'] ?? null;
$games = $_SESSION['numgames'] ?? null;
$wins = $_SESSION['numwins'] ?? null;
$loss = $_SESSION['numlosses'] ?? null;

$errors = array();


// Includes library, makes database connection,
include "includes/library.php";
$pdo = connectDB();

// checks for submit button being clicked
if(isset($_POST['submit'])){
    // checks if value was entered
    if(!isset($name) || strlen($name) === 0){
        $errors['name'] = true;
    }
    // only proceeds if there are no errors
    if(count($errors)===0){ 
    // inserts players score into the database 
    $query = "INSERT into high_scores values (NULL ,?,?,?,?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$name, $games, $wins, $loss]);
    // redirects to high score page
    header("Location: highscores.php");
    exit();
    }

}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Game Over - Play Rock Paper Scissors Free Online</title>
        <link rel="stylesheet" href="styles/master.css" />
    </head>
    <body>
        <!-- include header -->
        <?php include "includes/header.php";?>
        <main>
            <!-- include header -->
            <?php include "includes/nav.php";?>
            <section>
                <h1>Game Over<h1>
                <h2>Your Score</h2>
                <!-- An overview of the player's score -->
                <div id="scorecontainer">
                    <div id="gamesplayed">
                        <p><?php echo $games; ?> games</p>
                    </div>
                    <div id="wins">
                        <p><?php echo $wins; ?> wins</p>
                    </div>
                    <div id="losses">
                        <p><?php echo $loss; ?> losses</p> 
                    </div>
                </div>
                <!-- Form where player can enter their name to save their score -->
                <form id="scoresave" name="scoresave" method="POST">
                    <h2>Add to High Scores<h2>
                    <label for="name" id="name">Name:</label>
                    <input type="text" id="name" name="name">
                    <button type="submit" name="submit">Submit</button> 
                </form>
                <!-- error that shows if no name is entered before submitting form -->
                <span class="error <?=!isset($errors['name']) ? 'hidden' : "";?>">Please enter your name</span>
            </section>
        </main>
        <!-- includes footer -->
        <?php include "includes/footer.php";?>
    </body>
</html>
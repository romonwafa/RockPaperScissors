<?php
// starts session
session_start();

//error array for storing errors
$errors = array();
$rpsarray = array("Rock", "Paper", "Scissors");
// preload rock, paper, or scissors randomly from array above
$randomdraw = $rpsarray[rand(0,2)];

// sets $choice variable value
$choice = $_POST['choice'] ?? null;

// unset session on GET request
if($_SERVER['REQUEST_METHOD'] === "GET"){
    session_unset();
}

// initialize session keys
if(!array_key_exists('numgames', $_SESSION)){
    $_SESSION['numgames'] = 0;
}

if(!array_key_exists('numwins', $_SESSION)){
    $_SESSION['numwins'] = 0;
}

if(!array_key_exists('numlosses', $_SESSION)){
    $_SESSION['numlosses'] = 0;
}

// Update the score board's number of games, wins and losses
// This function scans the string returned by the determineWinner
// function below to update the appropriate value  
function updateScore($resultmsg){
    
    if(strpos($resultmsg, "win") !== false && $_SESSION['numlosses'] < 10){
        $_SESSION['numwins']++;
        $_SESSION['numgames']++;
        return "Game number: " . $_SESSION['numgames'] . " Wins:  " . $_SESSION['numwins'] . "  Losses: " . $_SESSION['numlosses'];
    }        
    elseif(strpos($resultmsg, "lose") !== false && $_SESSION['numlosses'] < 10){
            $_SESSION['numlosses']++;
            $_SESSION['numgames']++;
            return "Game number: " . $_SESSION['numgames'] . " Wins:  " . $_SESSION['numwins'] . "  Losses: " . $_SESSION['numlosses'];
    }
    elseif(strpos($resultmsg, "Tie") !== false && $_SESSION['numlosses'] < 10){
        $_SESSION['numgames']++;
        return "Game number: " . $_SESSION['numgames'] . " Wins:  " . $_SESSION['numwins'] . "  Losses: " . $_SESSION['numlosses'];
    }
    else {
        header("Location: gameover.php");
        exit();
    }

}

// Determines the winnner based on user selection and
// random element from rock paper scissors array
// Returns message to be displayed to player in feedback area
function determineWinner($opponentdraw, $playerdraw){
    $i = $opponentdraw.$playerdraw;
    $tie = "\r\n\r\n Tie! \r\n\r\n";
    $win = "\r\n\r\n You win! \r\n\r\n";
    $lose = "\r\n\r\n You lose. \r\n\r\n";
    $oppmsg = "Opponent draws: $opponentdraw";
    $plrmsg = "You drew: $playerdraw";
    $tiemsg = $oppmsg.$tie.$plrmsg;
    $winmsg = $oppmsg.$win.$plrmsg;
    $losemsg = $oppmsg.$lose.$plrmsg;

    switch($i) {
        case "RockRock":
            return $tiemsg;
            break;
        case "RockPaper":
            return $winmsg;
            break;
        case "RockScissors":
            return $losemsg;
            break;
        case "PaperRock":
            return $losemsg;
            break;
        case "PaperPaper":
            return $tiemsg;
            break;
        case "PaperScissors":
            return $winmsg;
            break;
        case "ScissorsRock":
            return $winmsg;
            break;
        case "ScissorsPaper":
            return $losemsg;
            break;
        case "ScissorsScissors":
            return $tiemsg;
            break;
        }
}

// checks for submit
if (isset($_POST['submit'])) {
    // if the player doesn't make a selection, add error to error array
    if (empty($choice)) {
        $errors['choice'] = true;
    }
    // if there are no errors in the error array, proceed
    if(count($errors)===0){
        // calls function to determine winner
        // passes the randomly selected array element and user choice to function
        $resultmsg = determineWinner($randomdraw, $choice);
        // Passes the result message from determineWinner funtion
        // to updateScore function
        $scoremsg = updateScore($resultmsg);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Play Rock Paper Scissors Free Online</title>
        <link rel="stylesheet" href="styles/master.css" />
    </head>
    <body>
        <!-- includes header -->
        <?php include "includes/header.php";?>
        <main>
            <!-- includes nav -->
            <?php include "includes/nav.php";?>
            <section>
                <!-- This is the window the gives feedback e.g what was picked, lose or win -->
                <div id="gamewindow">
                    <?php if(!empty($resultmsg)){echo nl2br($resultmsg);}else{ echo "Opponent is ready.";} ?>
                </div>
                <!-- This is the window below the feedback window. It shows the current score --> 
                <div id="tracker">
                    <?php if(!empty($scoremsg)){echo $scoremsg;}else{echo "Scoreboard Ready";} ?>
                </div>
                <!-- This is the form the user interacts with to make a selection for the game -->
                <form id="game" name="game" method="POST">
                    <fieldset>
                        <legend>Choice</legend>
                        <div>
                            <input type="radio" name="choice" id="rock" value="Rock" <?=$choice=="rock" ? 'checked': '' ?> />
                            <label for="rock">Rock</label>
                        </div>
                        <div>
                            <input type="radio" name="choice" id="paper" value="Paper" <?=$choice=="paper" ? 'checked' : '' ?> />
                            <label for="paper">Paper</label>
                        </div>
                        <div>
                            <input type="radio" name="choice" id="scissors" value="Scissors" <?=$choice=="scissors" ? 'checked' : '' ?> />
                            <label for="scissors">Scissors</label>
                        </div>
                        <span class="error <?=!isset($errors['choice']) ? 'hidden' : "";?>">Please select an option</span>
                    </fieldset>
                    <!-- triggers the submission routine in the php code -->
                    <button type="submit" name="submit">Submit</button> 
                </form>
            </section>
        </main>
        <!-- Includes footer -->
        <?php include "includes/footer.php";?>
    </body>
</html>
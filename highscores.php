<?php
// starts session
session_start();


// Includes library, makes database connection,
include "includes/library.php";
$pdo = connectDB();

// Selects 20 rows with most wins
$query = "SELECT * FROM high_scores ORDER BY wins DESC LIMIT 20";
$stmt = $pdo->prepare($query);
$stmt->execute();

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>High Scores - Play Rock Paper Scissors Free Online</title>
        <link rel="stylesheet" href="styles/master.css" />
    </head>
    <body>
        <?php include "includes/header.php";?>
        <main>
            <?php include "includes/nav.php";?>
            <section>
                <h1>High Scores</h1>
                <!-- make table for high scores -->
                <table>
                    <!-- table header -->
                    <tr>
                        <th>Name</th>
                        <th>Wins</th>
                        <th>losses</th>
                        <th>numGames</th>
                    </tr>
                    <!-- adds rows in table for the top 20 wins in the database -->
                    <?php foreach ($stmt as $row): ?>
                    <tr><td><?=$row['name']?></td><td><?=$row['wins']?></td><td><?=$row['losses']?></td><td><?=$row['numGames']?></td></tr>
                    <?php endforeach?>
                </table>
            </section>
        </main>
        <!-- includes footer -->
        <?php include "includes/footer.php";?>
    </body>
</html>
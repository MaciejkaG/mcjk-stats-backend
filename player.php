<?php
    include("mysql-lib.php");

    if (isset($_GET["name"])) {
        $config = parse_ini_file('../../../mc-config.ini');
        $conn = new mysqli($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_database"], $config["db_port"]);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $playerSQL = get_one_row($conn, "SELECT * FROM player_stats WHERE LOWER(display_name) = '".strtolower($_GET["name"])."'");
        if (!$playerSQL) {
            http_response_code(404);
        } else {
            http_response_code(200);
        }
    } else {
        http_response_code(400);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code&family=Poppins:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>MaciejkaAuth - Wyszukiwanie gracza</title>
</head>
<body>
    <span class="designerTag">Designed by Maciejka / Powered by MCJK-Stats</span>
    <div class="container">
        <h3 class="labelAbove">Statystyki gracza:</h3>
        <h1 class="playerName"><?php if ($playerSQL) {echo $playerSQL["display_name"];} else {echo $_GET["name"];} ?></h1>
        <?php
            if ($playerSQL) {
                echo '
                <p>
                    K/D: <span class="markedText">'.$playerSQL["kills"].'/'.$playerSQL["deaths"].'</span><br>
                    Zabite moby: <span class="markedText">'.$playerSQL["mob_kills"].'</span><br>
                    Postawione bloki: <span class="markedText">'.$playerSQL["blocks_placed"].'</span><br>
                    Zniszczone bloki: <span class="markedText">'.$playerSQL["blocks_broken"].'</span><br>
                    Miejsce w rankingu (wg. killi): <span class="markedText">null</span><br>
                    Czas gry (H:M): <span class="markedText">'.gmdate("H:i", $playerSQL["time_played"]*60).'</span>
                </p>
                ';
            } else {
                echo '<h3>Nie znaleziono takiego gracza</h3>';
            }
        ?>
        <button onclick="window.location.href = 'index.html'">Wróć do wyszukiwania</button>
    </div>
</body>
</html>
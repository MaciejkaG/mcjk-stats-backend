<?php
    session_start();
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
    <title>MaciejkaAuth - Wyszukiwanie gracza</title>
    <style>
        :root {
            font-size: 20px;

            --theme-color: rgb(187, 57, 238);
        }

        body {
            background: black;
            color: white;
            font-family: 'Fira Code', monospace;
        }

        h1,h2,h3,h4,h5,h6 {
            font-family: 'Poppins', sans-serif;
        }

        .container {
            display: flex;
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            justify-content: center;
            align-items: center;
            text-align: center;
            flex-direction: column;
        }

        .labelAbove {
            opacity: 0.3;
            margin-bottom: 0;
        }

        .playerName {
            margin-top: 0;
            color: var(--theme-color);
        }

        .markedText {
            color: var(--theme-color);
        }

        button {
            font-size: 0.8rem;
            padding: 0.5em 1em;
            border-radius: 0.5rem;
            color: white;
            border: none;
            outline: none;
            font-family: 'Fira Code', monospace;
            transition: all 0.3s;
            margin-top: 0.5rem;
            background: var(--theme-color);
            cursor: pointer;
        }

        button:hover {
            background: rgb(156, 48, 199);
        }
    </style>
</head>
<body>
    <div class="container">
        <h3 class="labelAbove">Wyszukiwanie gracza:</h3>
        <h1 class="playerName">MaciejkaG</h1>
        <?php
            if ($playerSQL) {
                echo '
                <p>
                    K/D: <span class="markedText">'.$playerSQL["kills"].'/'.$playerSQL["deaths"].'</span><br>
                    Zabite moby: <span class="markedText">'.$playerSQL["mob_kills"].'</span><br>
                    Postawione bloki: <span class="markedText">'.$playerSQL["blocks_placed"].'</span><br>
                    Zniszczone bloki: <span class="markedText">'.$playerSQL["blocks_broken"].'</span><br>
                    Miejsce w rankingu (wg. killi): <span class="markedText">null</span><br>
                    Czas gry (H:M): <span class="markedText">'.gmdate("H:i", $playerSQL["time_played"]).'</span>
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
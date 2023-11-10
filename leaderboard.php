<?php
    include("api/mysql-lib.php");

    $config = parse_ini_file('../../../mc-config.ini');

    $conn = new mysqli($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_database"], $config["db_port"]);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $order = "";

    if (!isset($_GET["sort"]) ||$_GET["sort"]=="kills_desc" ) {
        $order = "ORDER BY kills DESC";
    } elseif ($_GET["sort"]=="kills_asc") {
        $order = "ORDER BY kills ASC";
    } elseif ($_GET["sort"]=="deaths_desc") {
        $order = "ORDER BY deaths DESC";
    } elseif ($_GET["sort"]=="deaths_asc") {
        $order = "ORDER BY kills ASC";
    } elseif ($_GET["sort"]=="mk_desc") {
        $order = "ORDER BY mob_kills DESC";
    } elseif ($_GET["sort"]=="mk_asc") {
        $order = "ORDER BY mob_kills ASC";
    } elseif ($_GET["sort"]=="bp_desc") {
        $order = "ORDER BY blocks_placed DESC";
    } elseif ($_GET["sort"]=="bp_asc") {
        $order = "ORDER BY blocks_placed ASC";
    } elseif ($_GET["sort"]=="bb_desc") {
        $order = "ORDER BY blocks_broken DESC";
    } elseif ($_GET["sort"]=="bb_asc") {
        $order = "ORDER BY blocks_broken ASC";
    } elseif ($_GET["sort"]=="gt_desc") {
        $order = "ORDER BY time_played DESC";
    } elseif ($_GET["sort"]=="gt_asc") {
        $order = "ORDER BY time_played ASC";
    } else {
        $order = "ORDER BY kills DESC";
    }

    http_response_code(200);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code&family=Poppins:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>MaciejkaStats - Ranking graczy</title>
    <style>
        .container {
            box-sizing: border-box;
            justify-content: flex-start;
        }
        button {
            margin-top: 2rem;
        }
        p {
            font-size: 0.8rem;
            margin-top: 1.5rem;
            text-align: center;
            opacity: 0.6;
        }
    </style>
</head>

<body>
    <span class="designerTag">Designed by Maciejka / Powered by MCJK-Stats</span>
    <div class="container">
        <h1>Ranking graczy (Top 10)</h1>
        <select id="sorting">
            <option value="kills_desc">Kille malejąco</option>
            <option value="kills_asc">Kille rosnąco</option>
            <option value="deaths_desc">Śmierci malejąco</option>
            <option value="deaths_asc">Śmierci rosnąco</option>
            <option value="mk_desc">ZM malejąco</option>
            <option value="mk_asc">ZM rosnąco</option>
            <option value="bp_desc">PB malejąco</option>
            <option value="bp_asc">PB rosnąco</option>
            <option value="bb_desc">ZB malejąco</option>
            <option value="bb_asc">ZB rosnąco</option>
            <option value="gt_desc">CG malejąco</option>
            <option value="gt_asc">CG rosnąco</option>
        </select>
        <table>
            <tr>
                <th>Nazwa gracza</th>
                <th>K/D</th>
                <th>ZM</th>
                <th>PB</th>
                <th>ZB</th>
                <th>CG</th>
            </tr>
            <?php
            $sql = "SELECT * FROM player_stats $order LIMIT 10";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '
                    <tr onclick="window.location.href = \'player.php?name='.urlencode($row["display_name"]).'\'">
                        <td>'.$row["display_name"].'</td>
                        <td>'.$row["kills"].'/'.$row["deaths"].'</td>
                        <td>'.$row["mob_kills"].'</td>
                        <td>'.$row["blocks_placed"].'</td>
                        <td>'.$row["blocks_broken"].'</td>
                        <td>'.gmdate("H:i", $row["time_played"]*60).'</td>
                    </tr>
                    ';
                }
            }
            ?>
        </table>
        
        <button onclick="window.location.href = 'index.html'">Wróć do wyszukiwania</button>
        <p>
            K/D - Kills/Deaths<br>
            ZM - Zabite Moby<br>
            PB - Postawione Bloki<br>
            ZB - Zniszczone Bloki<br>
            CG - Czas Gry<br>
        </p>
    </div>

    <script>
        const select = document.getElementById("sorting");

        $(window).on("load", function() {
            let sorting = getSortingMode();

            if (sorting!==null) {
                $(select).val(sorting);
            }

            $(select).on("change", function() {
                setSortingMode($(this).val());
            })
        })

        function getSortingMode() {
            const params = new URLSearchParams(window.location.search);

            return params.get("sort");
        }

        function setSortingMode(mode) {
            const params = new URLSearchParams(window.location.search);

            params.set("sort", mode);
            window.location.replace(getBaseUrl(window.location.href) + "?" + params.toString());
        }

        function getBaseUrl(url) {
            var tempArray = url.split("?");
            var baseURL = tempArray[0];

            return baseURL;
        }
    </script>
</body>

</html>
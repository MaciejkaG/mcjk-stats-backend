<?php
    function get_one_row($conn, $query) {
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                return $row;
            }
        } else {
            return NULL;
        }
    }

    http_response_code(404);
?>
<!-- Author 1: Lei Zhao 041086365 -->
<!-- Author 2: Zhicheng He 041086226 -->
<!-- Author 3: Yuling Guo 041069402 -->
<!-- Author 4: Yu Song 040873597 -->
<!-- Section: CST8285_301 -->
<!-- Professor: Shehzeen Shehzeen -->
<!-- File name: removeTrack.php -->
<!-- Date: Jul. 23, 2023 -->
<!-- Description: removeTrack.php -->
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Group Work">
    <meta name=" description " content=" Assignment 2 CST8285 Web Programming ">
    <meta name=" keywords " content=" CSS, HTML, JavaScript, PHP, CST8285, Web Programming ">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:800|Work+Sans:400" rel="stylesheet">
    <title>MusicBox - Remove Track</title>
    <link rel="stylesheet" type="text/css" href="./jumping.css">
</head>

<body>

    <?php
    // Get the Previous Page URL
    $previousPageURL = $_SERVER['HTTP_REFERER'];

    // Parse the URL to extract the query string
    $queryString = parse_url($previousPageURL, PHP_URL_QUERY);

    // Parse the query string to get the individual parameters
    parse_str($queryString, $params);

    // Get the value of the 'id' parameter, if it exists
    $id = isset($params['id']) ? $params['id'] : null;

    // Validate and sanitize the user ID to prevent SQL injection
    if (!is_numeric($id) || $id <= 0) {
        echo "Invalid user ID.";
        exit();
    }

    $server_name = "localhost";
    $database_name = "assignment2";
    $user_name = "test";
    $user_password = "test";

    // Create a new database connection
    $connection = new mysqli($server_name, $user_name, $user_password, $database_name) or die($connection->connect_error);

    // ... Database connection and other necessary code ...

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        // Get the selected track IDs from the POST data
        if (isset($_POST['selectedTracks']) && is_array($_POST['selectedTracks'])) {
            $selectedTracks = $_POST['selectedTracks'];
            // Retrieve the current user's ID
            $userID = $id;
            // Prepare the SQL query with placeholders
            $sql = "DELETE FROM track_playlist WHERE id_track = ? AND in_id_playlist = ?";

            // Use prepared statements to bind the parameters securely
            $stmt = $connection->prepare($sql);
            $stmt->bind_param("ii", $trackID, $userID);

            // Loop through the selected track IDs and remove them from the playlist for the current user
            foreach ($selectedTracks as $trackID) {
                // Execute the query
                if ($stmt->execute() === false) {
                    echo "Error removing track from playlist: " . $stmt->error;
                    $stmt->close();
                    $connection->close();
                    exit();
                }
            }

            // Close the prepared statement
            $stmt->close();

            // Close the database connection
            $connection->close();

            // Successfully removed tracks from the playlist
            echo '<p class="jumping">Tracks removed from playlist successfully!';
            echo '<script>
            setTimeout(function() {
                window.location.href = "' . $previousPageURL . '";
            }, 1000); // 1000 milliseconds = 2 seconds
          </script>';
            exit();
        } else {
            // If no tracks are selected, display an error message or handle it accordingly
            echo "<p class='jumping'>No tracks selected.</p>";
            echo '<script>
            setTimeout(function() {
                window.location.href = "' . $previousPageURL . '";
            }, 1000); // 1000 milliseconds = 2 seconds
          </script>';
            exit();
        }
    }

    // Handle invalid or missing POST data
    echo "<p class='jumping'>Invalid request.</p>";
    echo '<script>
            setTimeout(function() {
                window.location.href = "' . $previousPageURL . '";
            }, 1000); // 1000 milliseconds = 2 seconds
          </script>';
    exit();
    ?>


    <footer>
        <!--<p id="CopyRight">Author: GROUP  Date: 2023-06-30 &copy;all copyright reserved</p>-->
    </footer>
</body>

</html>
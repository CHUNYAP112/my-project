
<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["query"])) {
        $query = $_POST["query"];
        // Perform a database query to get search results based on $query
        $sql = "SELECT package_name, category, destination FROM travelpackage WHERE package_name = '$query' OR category = '$query' OR destination '$query'";
        $result = $conn->query($sql);

        // Collect results in an array
        $results = array();

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $results[] = $row;
            }
        }

        // Return the results as JSON
        header('Content-Type: application/json');
        echo json_encode($results);
        exit();
    }
}
?>






<?php
$servername = "localhost";
$username = "root";
$database = "mkunstman_movies";
$password = "";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if (isset($_GET['id'])){
        $stmt = $conn->prepare("SELECT
  m.title,
  m.release_date,
  GROUP_CONCAT(a.name ORDER BY a.name ASC SEPARATOR ', ') AS actor_names
FROM
  movies m
JOIN 
  movie_actor ma ON m.movie_id = ma.movie_id
JOIN 
  actors a ON ma.actor_id = a.actor_id
WHERE
  m.movie_id = :id
GROUP BY
  m.movie_id, m.title, m.release_date;
");

$stmt->bindParam(':id', $_GET['id']);

    }
    else 
    {
        $stmt = $conn->prepare("SELECT m.title FROM movies m");
    } 

    $stmt->execute();

    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

    echo json_encode($stmt->fetchAll());
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

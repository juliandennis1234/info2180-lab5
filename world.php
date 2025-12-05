<?php
$host = 'localhost';
$username = 'lab5_user';
$password = 'password123';
$dbname = 'world';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$country = $_GET['country'] ?? "";
$lookup = $_GET['lookup'] ?? "";

if ($lookup === "cities") {
    // Lookup cities
    if ($country === "") {
        $stmt = $conn->query("
            SELECT c.name AS city, c.district, c.population
            FROM cities c
            JOIN countries co ON c.country_code = co.code
        ");
    } else {
        $stmt = $conn->prepare("
            SELECT c.name AS city, c.district, c.population
            FROM cities c
            JOIN countries co ON c.country_code = co.code
            WHERE co.name LIKE :country
        ");
        $stmt->execute(['country' => "%$country%"]);
    }

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($results) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>City Name</th>
                    <th>District</th>
                    <th>Population</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['city']) ?></td>
                        <td><?= htmlspecialchars($row['district']) ?></td>
                        <td><?= number_format($row['population']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No cities found.</p>
    <?php endif; ?>

<?php
} else {
    // Lookup countries
    if ($country === "") {
        $stmt = $conn->query("SELECT * FROM countries");
    } else {
        $stmt = $conn->prepare("SELECT * FROM countries WHERE name LIKE :country");
        $stmt->execute(['country' => "%$country%"]);
    }

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($results) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Country Name</th>
                    <th>Continent</th>
                    <th>Independence Year</th>
                    <th>Head of State</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['continent']) ?></td>
                        <td><?= htmlspecialchars($row['independence_year'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($row['head_of_state']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No results found.</p>
    <?php endif;
}
?>

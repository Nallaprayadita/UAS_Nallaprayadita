<?php
session_start();
include '../config/database.php';

echo "<h3>Debug Database Structure</h3>";

// Cek struktur tabel orders
echo "<h4>Struktur Tabel Orders:</h4>";
$result = mysqli_query($conn, "DESCRIBE orders");
if ($result) {
    echo "<table border='1'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Error: " . mysqli_error($conn);
}

// Cek struktur tabel users
echo "<h4>Struktur Tabel Users:</h4>";
$result = mysqli_query($conn, "DESCRIBE users");
if ($result) {
    echo "<table border='1'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Error: " . mysqli_error($conn);
}

// Cek struktur tabel products
echo "<h4>Struktur Tabel Products:</h4>";
$result = mysqli_query($conn, "DESCRIBE products");
if ($result) {
    echo "<table border='1'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Error: " . mysqli_error($conn);
}

// Cek struktur tabel order_items
echo "<h4>Struktur Tabel Order Items:</h4>";
$result = mysqli_query($conn, "DESCRIBE order_items");
if ($result) {
    echo "<table border='1'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Error: " . mysqli_error($conn);
}

// Cek data sample
echo "<h4>Sample Data Orders:</h4>";
$result = mysqli_query($conn, "SELECT * FROM orders LIMIT 5");
if ($result) {
    echo "<table border='1'>";
    if (mysqli_num_rows($result) > 0) {
        $first_row = mysqli_fetch_assoc($result);
        echo "<tr>";
        foreach ($first_row as $key => $value) {
            echo "<th>$key</th>";
        }
        echo "</tr>";
        
        echo "<tr>";
        foreach ($first_row as $key => $value) {
            echo "<td>$value</td>";
        }
        echo "</tr>";
        
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            foreach ($row as $key => $value) {
                echo "<td>$value</td>";
            }
            echo "</tr>";
        }
    } else {
        echo "<tr><td>No data found</td></tr>";
    }
    echo "</table>";
} else {
    echo "Error: " . mysqli_error($conn);
}

echo "<h4>Sample Data Users:</h4>";
$result = mysqli_query($conn, "SELECT * FROM users LIMIT 5");
if ($result) {
    echo "<table border='1'>";
    if (mysqli_num_rows($result) > 0) {
        $first_row = mysqli_fetch_assoc($result);
        echo "<tr>";
        foreach ($first_row as $key => $value) {
            echo "<th>$key</th>";
        }
        echo "</tr>";
        
        echo "<tr>";
        foreach ($first_row as $key => $value) {
            echo "<td>$value</td>";
        }
        echo "</tr>";
        
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            foreach ($row as $key => $value) {
                echo "<td>$value</td>";
            }
            echo "</tr>";
        }
    } else {
        echo "<tr><td>No data found</td></tr>";
    }
    echo "</table>";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>

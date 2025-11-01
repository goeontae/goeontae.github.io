<?php
header("Content-Type: application/json");

// 配置数据库（请替换为您的数据库信息）
$servername = "localhost";
$username = "root";
$password = "your_password";
$dbname = "zpyz_persons";

// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode([]);
    exit;
}

// 查询数据
$sql = "SELECT * FROM persons ORDER BY create_time DESC";
$result = $conn->query($sql);

$persons = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $persons[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'gradYear' => $row['grad_year'],
            'category' => $row['category'],
            'profession' => $row['profession'],
            'achievement' => $row['achievement'],
            'photo' => $row['photo'],
            'createTime' => $row['create_time']
        ];
    }
}

// 关闭连接
$conn->close();

echo json_encode($persons);
?>
<?php
header('Content-Type: application/json');

// 数据存储目录
$dataDir = 'data/';
$photoDir = 'photos/';

// 确保目录存在
if (!is_dir($dataDir)) {
    mkdir($dataDir, 0755, true);
}
if (!is_dir($photoDir)) {
    mkdir($photoDir, 0755, true);
}

// 单个人物ID查询
$personId = isset($_GET['id']) ? $_GET['id'] : null;

if ($personId) {
    $filePath = $dataDir . $personId . '.json';
    if (file_exists($filePath)) {
        $personData = json_decode(file_get_contents($filePath), true);
        echo json_encode($personData);
    } else {
        echo json_encode(null);
    }
    exit;
}

// 获取所有人物
$persons = array();
$files = glob($dataDir . '*.json');

foreach ($files as $file) {
    $personData = json_decode(file_get_contents($file), true);
    if ($personData) {
        $persons[] = $personData;
    }
}

echo json_encode($persons);
?>
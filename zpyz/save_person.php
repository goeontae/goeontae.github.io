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

// 验证必填字段
$requiredFields = ['name', 'gradYear', 'category', 'achievement'];
foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
        echo json_encode([
            'success' => false,
            'message' => '请填写所有必填字段'
        ]);
        exit;
    }
}

// 处理照片上传
$photoPath = '';
if (!empty($_FILES['photo']['name'])) {
    $allowedTypes = ['image/jpeg', 'image/png'];
    $maxSize = 5 * 1024 * 1024; // 5MB
    
    if (!in_array($_FILES['photo']['type'], $allowedTypes)) {
        echo json_encode([
            'success' => false,
            'message' => '照片格式不正确，仅支持JPG和PNG'
        ]);
        exit;
    }
    
    if ($_FILES['photo']['size'] > $maxSize) {
        echo json_encode([
            'success' => false,
            'message' => '照片大小超过限制，最大支持5MB'
        ]);
        exit;
    }
    
    // 生成唯一文件名
    $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $extension;
    $destination = $photoDir . $filename;
    
    if (move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
        $photoPath = $photoDir . $filename;
    } else {
        echo json_encode([
            'success' => false,
            'message' => '照片上传失败'
        ]);
        exit;
    }
}

// 准备人物数据
$personData = [
    'id' => uniqid(),
    'name' => trim($_POST['name']),
    'gradYear' => trim($_POST['gradYear']),
    'className' => trim($_POST['className'] ?? ''),
    'category' => trim($_POST['category']),
    'achievement' => trim($_POST['achievement']),
    'photo' => $photoPath,
    'timestamp' => time()
];

// 保存数据到JSON文件
$filePath = $dataDir . $personData['id'] . '.json';
if (file_put_contents($filePath, json_encode($personData, JSON_UNESCAPED_UNICODE))) {
    echo json_encode([
        'success' => true,
        'message' => '人物信息保存成功'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => '数据保存失败，请稍后重试'
    ]);
}
?>
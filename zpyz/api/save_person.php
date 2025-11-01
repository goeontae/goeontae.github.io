<?php
header("Content-Type: application/json");
$response = ['success' => false, 'message' => ''];

// 检查请求方法
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = '请使用POST方法提交';
    echo json_encode($response);
    exit;
}

// 验证必填字段
if (empty($_POST['name']) || empty($_POST['gradYear']) || empty($_POST['achievement'])) {
    $response['message'] = '姓名、毕业年份和主要成就是必填项';
    echo json_encode($response);
    exit;
}

// 配置数据库（请替换为您的数据库信息）
$servername = "localhost";
$username = "root";
$password = "your_password";
$dbname = "zpyz_persons";

// 创建数据库连接
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    $response['message'] = '数据库连接失败: ' . $conn->connect_error;
    echo json_encode($response);
    exit;
}

// 处理文件上传
$photo = '';
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../uploads/';
    // 创建上传目录（如果不存在）
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    // 验证文件类型和大小
    $fileInfo = pathinfo($_FILES['photo']['name']);
    $allowedExts = ['jpg', 'jpeg', 'png'];
    $fileExt = strtolower($fileInfo['extension']);
    
    if (!in_array($fileExt, $allowedExts)) {
        $response['message'] = '只允许上传JPG、PNG格式的图片';
        echo json_encode($response);
        $conn->close();
        exit;
    }
    
    if ($_FILES['photo']['size'] > 2 * 1024 * 1024) {
        $response['message'] = '图片大小不能超过2MB';
        echo json_encode($response);
        $conn->close();
        exit;
    }
    
    // 生成唯一文件名
    $fileName = uniqid() . '.' . $fileExt;
    $targetPath = $uploadDir . $fileName;
    
    // 移动上传文件
    if (!move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {
        $response['message'] = '图片上传失败';
        echo json_encode($response);
        $conn->close();
        exit;
    }
    
    $photo = $fileName;
}

// 准备SQL语句
$sql = "INSERT INTO persons (name, grad_year, category, profession, achievement, photo, create_time) 
        VALUES (?, ?, ?, ?, ?, ?, NOW())";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ssssss",
    $_POST['name'],
    $_POST['gradYear'],
    $_POST['category'],
    $_POST['profession'],
    $_POST['achievement'],
    $photo
);

// 执行SQL
if ($stmt->execute()) {
    $response['success'] = true;
    $response['message'] = '保存成功';
} else {
    $response['message'] = '数据库错误: ' . $stmt->error;
}

// 关闭连接
$stmt->close();
$conn->close();

echo json_encode($response);
?>
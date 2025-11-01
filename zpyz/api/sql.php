CREATE DATABASE IF NOT EXISTS zpyz_persons DEFAULT CHARACTER SET utf8mb4;

USE zpyz_persons;

CREATE TABLE IF NOT EXISTS persons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL COMMENT '姓名',
    grad_year VARCHAR(50) NOT NULL COMMENT '毕业年份',
    category VARCHAR(20) COMMENT '类别：student-校友，teacher-教师',
    profession VARCHAR(100) COMMENT '职业/职务',
    achievement TEXT NOT NULL COMMENT '主要成就与事迹',
    photo VARCHAR(255) COMMENT '照片文件名',
    create_time DATETIME NOT NULL COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='邹平一中人物志';
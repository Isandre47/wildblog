<?php
// Add admin hard bd, password bcrypt


$pdo = new PDO("mysql:host=localhost;dbname=project2", "root", "claude05");

$statement = $pdo->prepare("insert into user (firstname, lastname, email, pass, registered, status) VALUES ('admin', 'admin', 'super@root.com', :password, NOW(), 'admin' )");
$statement->bindValue('password', password_hash('demo',PASSWORD_DEFAULT));
$statement->execute();
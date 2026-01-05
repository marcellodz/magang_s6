<?php
require_once "koneksi.php";
header('Content-Type: application/json');

$response = ["success" => false, "field" => "", "message" => ""];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $hp = trim($_POST["hp"] ?? "");

    if ($email !== "") {
        $stmt = $conn2->prepare("SELECT COUNT(*) FROM super_user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            echo json_encode(["success" => false, "field" => "email", "message" => "Email ini sudah terdaftar."]);
            exit;
        }
    }

    if ($hp !== "") {
        $stmt = $conn2->prepare("SELECT COUNT(*) FROM super_user WHERE hp = ?");
        $stmt->bind_param("s", $hp);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            echo json_encode(["success" => false, "field" => "hp", "message" => "Nomor WhatsApp ini sudah digunakan."]);
            exit;
        }
    }

    echo json_encode(["success" => true]);
}

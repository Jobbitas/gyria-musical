<?php
$data = json_decode(file_get_contents('php://input'), true);
$valor = $data['valor'] ?? 0;
if ($valor > 0) {
  $db = new PDO('sqlite:produtos.db');
  $stmt = $db->prepare("INSERT INTO vendas (valor, data) VALUES (?, date('now'))");
  $stmt->execute([$valor]);
  echo json_encode(['status' => 'ok']);
} else {
  http_response_code(400);
  echo json_encode(['erro' => 'Valor inválido']);
}
?>
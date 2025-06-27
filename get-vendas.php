<?php
session_start();
if (!isset($_SESSION['admin'])) {
  http_response_code(403);
  echo json_encode(['erro' => 'Acesso negado']);
  exit;
}
$db = new PDO('sqlite:produtos.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$stmt = $db->query("SELECT strftime('%w', data) as dia_semana, SUM(valor) as total
                    FROM vendas
                    WHERE strftime('%Y-%m-%d', data) >= date('now', '-6 days')
                    GROUP BY dia_semana
                    ORDER BY dia_semana");
$labels = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
$valores = array_fill(0, 7, 0);
foreach ($stmt as $row) {
  $dia = (int)$row['dia_semana'];
  $valores[$dia] = (float)$row['total'];
}
header('Content-Type: application/json');
echo json_encode(['labels' => array_values($labels), 'valores' => array_values($valores)]);
?>
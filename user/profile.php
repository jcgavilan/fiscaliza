<?php
require_once '../config/cors.php';
require_once '../middleware/auth.php';

echo json_encode([
    'ok'=>true,
    'user'=>$payload
]);

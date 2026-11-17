<?php

header("Content-Type: application/json; charset=utf-8");

$ruta = $_GET["ruta"] ?? "";

if (!preg_match('#^character(/\d+)?$#', $ruta)) {
    http_response_code(400);
    echo json_encode(["error" => "Ruta no permitida"]);
    exit;
}

$url = "https://rickandmortyapi.com/api/" . $ruta;
if (isset($_GET["page"]) && ctype_digit($_GET["page"])) {
    $url .= "?page=" . $_GET["page"];
}

$contexto = stream_context_create(["http" => ["ignore_errors" => true, "timeout" => 10]]);
$respuesta = @file_get_contents($url, false, $contexto);

if ($respuesta === false) {
    http_response_code(502);
    echo json_encode(["error" => "No se pudo conectar con la API"]);
    exit;
}


if (isset($http_response_header[0]) && preg_match('#\s(\d{3})\s#', $http_response_header[0], $m)) {
    http_response_code((int)$m[1]);
}
echo $respuesta;

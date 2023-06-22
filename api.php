<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jsonData = file_get_contents('php://input');
    $data = json_decode($jsonData, true);
    if ($data !== null && isset($data['url']) && isset($data['port'])) {
        if (filter_var($data['url'], FILTER_VALIDATE_URL) !== false && preg_match('/^[0-9]+$/', $data['port'])) {
            // * Get data
            $host = parse_url($data['url'], PHP_URL_HOST);
            $ret = scanPort($host, $data['port']);
            echo json_encode($ret);
        } else {
            // * Invalid URL format
            echo json_encode(['error' => 'Invalid Data format']);
        }
    } else {
        // * Incorrect data
        echo json_encode(['error' => 'Incorrect data']);
    }
}

function scanPort($host, $port)
{
    // * check connection
    $connection = @fsockopen($host, $port, $errorCode, $errorString, 1);

    if (is_resource($connection)) {
        return "Port $port is open on $host.\n";
        fclose($connection);
    } else {
        return "Port $port is closed on $host.\n";
    }
}
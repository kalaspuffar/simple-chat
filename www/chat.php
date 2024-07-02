<?php
require_once(__DIR__ . '/../etc/config.php');

$data = json_decode(file_get_contents('php://input'));

if (isset($data->messages)) {
    $request = [
        "model" => "gpt-4o",
        "messages" => $data->messages,
        "n" => 1,
    ];
    
    $resquestJSON = json_encode($request);

    $headers = [
        "Authorization: Bearer " . $OPENAI_API_KEY
    ];

    $result = curlCall('https://api.openai.com/v1/chat/completions', 'POST', $headers, $resquestJSON);

    echo $result[1];
}

function curlCall($url, $method, $headers, $data) {
    array_push($headers, 'Content-Type: application/json');
    array_push($headers, 'Content-Length: ' . strlen($data));
    array_push($headers, 'User-Agent: Chat/0.1');

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    $curl_error = curl_error($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);

    if ($curl_error) {
        $response = [
            "status_code" => $info['http_code'],
            "message" => $curl_error
        ];
        return [$info['http_code'], json_encode($response)];
    } else {
        return [$info['http_code'], $response];
    }
}
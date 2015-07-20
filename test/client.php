<?php
$client = new swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_SYNC);

if (!$client->connect('127.0.0.1', 8001, -1)) {
    exit("connect failed. Error: {$client->errCode}\n");
}

$header_str = pack('SSLa16LL', 1, 1, 1, 'swoole', 0xfb709394, 1);
$body_arr = array(
    'k' => 123456,
);
$body_str = msgpack_pack($body_arr);
$request = $header_str . $body_str . "\r\n\r\n";

$client->send($request);
echo $client->recv();

$client->close();

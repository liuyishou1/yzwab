<?php

$bknFilePath = 'bkn.txt';
$bkn = file_get_contents($bknFilePath);

$cookieFilePath = 'cookie.txt';
$cookie = file_get_contents($cookieFilePath);


$url = 'https://qun.qq.com/cgi-bin/qunwelcome/myinfo?callback=&bkn='. $bkn;


$flagFilePath = 'request_flag.txt';


if (!file_exists($flagFilePath)) {

    $ch = curl_init();


    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIE, $cookie);

    // 执行请求
    $response = curl_exec($ch);

    // 检查错误
    if (curl_errno($ch)) {
        echo 'cURL Error: '. curl_error($ch);
    } else {
        // 处理响应
        echo $response;
    }

    curl_close($ch);

    file_put_contents($flagFilePath, 'request in progress');
} else {

    while (true) {

        $ch = curl_init();


        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);


        $response = curl_exec($ch);


        if (curl_errno($ch)) {
            echo 'cURL Error: '. curl_error($ch);
        } else {

            echo $response;
        }


        curl_close($ch);

        sleep(30);
    }
}
?>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['qq']) && isset($_GET['key'])) {
    $gc_value = $_GET['qq'];
    $key_value = $_GET['key'];

    $cookie_file = 'cookie.txt';
    $cookie_content = file_get_contents($cookie_file);
    $cookie = trim($cookie_content);


    $bkn_file = 'bkn.txt';
    $bkn_content = file_get_contents($bkn_file);
    $bkn = trim($bkn_content);

    $ch = curl_init();
    $url = 'https://qun.qq.com/cgi-bin/qun_mgr/search_group_members';
    $data = "gc=$gc_value&st=0&end=20&sort=0&key=$key_value&bkn=$bkn";
    $url.= "?$data";  

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
    curl_setopt($ch, CURLOPT_COOKIE, $cookie);

    $response = curl_exec($ch);
    if ($response === false) {
        echo 'Curl error: '. curl_error($ch);
    } else {
        $resultArray = json_decode($response, true);
        if (isset($resultArray['ec']) && $resultArray['ec']!= 0) {
            echo 'Cookie失效或群不存在';
            return;
        }
        $judge = 'no';
        if (isset($resultArray['mems']) && is_array($resultArray['mems'])) {
            foreach ($resultArray['mems'] as $member) {
                if ($member['uin'] == $key_value) {
                    $judge = 'yes';
                    break;
                }
            }
        }
        $result = [
            'qq' => $key_value,
            'judge' => $judge
        ];
        echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    curl_close($ch);
} else {
    echo "版权归回忆所\nQQ:3436017718";
}
?>
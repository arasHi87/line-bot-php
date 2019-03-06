<?php
// error_reporting(0); // 不顯示錯誤 (Debug 時請註解掉)
date_default_timezone_set("Asia/Taipei"); // 設定時區為台北時區

require_once('LINEBotTiny.php');

if (file_exists(__DIR__ . '/config.php')) {
    $config = include __DIR__ . '/config.php'; // 引入設定檔
    if ($config['channelAccessToken'] == Null || $config['channelSecret'] == Null) {
        error_log("config.php 設定檔內的 channelAccessToken 和 channelSecret 尚未設定完全！", 0); // 輸出錯誤
    } else {
        $channelAccessToken = $config['channelAccessToken'];
        $channelSecret = $config['channelSecret'];
    }
} else {
    $configFile = fopen("config.php", "w") or die("Unable to open file!");
    $configFileContent = "<?php
return [
    'channelAccessToken' => '',
    'channelSecret' => ''
];";
    fwrite($configFile, $configFileContent); // 建立文件並寫入
    fclose($configFile); // 關閉文件
    error_log("config.php 設定檔建立成功，請編輯檔案輸入 channelAccessToken 和 channelSecret！", 0); // 輸出錯誤
}

$client = new LINEBotTiny($channelAccessToken, $channelSecret);
$set    = json_decode(file_get_contents('set.json'));

foreach ($client->parseEvents() as $event) {
    switch ($event['type']) {
        case 'message':
            $message = $event['message'];
            switch ($message['type']) {
                case 'text':
                    // if (strtolower($message['text']) == 'text') {
                    //     $client->replyMessage([
                    //         'replyToken' => $event['replyToken'],
                    //         'messages'   => [
                    //             [
                    //                 'type' => 'text',
                    //                 'text' => 'Hello world!'
                    //             ]
                    //         ]
                    //     ]);
                    // }
                    switch (strtolower($message['text'])) {
                        case 'js@test':
                            $client->replyMessage([
                                'replyToken' => $event['replyToken'],
                                'messages'   => [
                                    [
                                        'type' => 'text',
                                        'text' => 'Test Successful!'
                                    ]
                                ]
                            ]);
                            break;
                        
                        default:
                            # code...
                            break;
                    }
                    break;

                default:
                    //error_log("Unsupporeted message type: " . $message['type']);
                    break;
            }
            break;
        
        case 'postback':
            //require_once('postback.php'); // postback
            break;
        
        default:
            //error_log("Unsupporeted event type: " . $event['type']);
            $client->replyMessage([
                'replyToken' => $event['replyToken'],
                'messages'   => [
                    [
                        'type' => 'text',
                        'text' => $set->hello
                    ]
                ]
            ]);
            break;
    }
};
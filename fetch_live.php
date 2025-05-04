<?php

$channels = [
    '華視新聞' => 'https://www.youtube.com/@CtsTw',
    'Muse木棉花-闔家歡' => 'https://www.youtube.com/@Muse_Family',
    'Muse木棉花-TW' => 'https://www.youtube.com/@MuseTW/streams'
];

$results = [];

function fetchHtml($url) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
        CURLOPT_SSL_VERIFYPEER => false,
    ]);
    $html = curl_exec($ch);
    curl_close($ch);
    return $html;
}

foreach ($channels as $name => $url) {
    $html = fetchHtml($url);
    if (!$html) continue;

    // 嘗試從HTML中找出 "isLive": true 的影片
    // 這段用來匹配 `isLive` 標籤並抓取視頻 ID
    if (preg_match('/"url":"(\/watch\?v=[^"]+)".*?"isLive":true/', $html, $matches)) {
        $videoPath = stripslashes($matches[1]);
        $fullUrl = 'https://www.youtube.com' . $videoPath;
        $results[] = "$name,$fullUrl";
    }
}

// 若有直播，寫入 live_streams.txt，否則寫入提示訊息
if (empty($results)) {
    file_put_contents('live_streams.txt', "目前無直播\n");
} else {
    file_put_contents('live_streams.txt', implode(PHP_EOL, $results) . "\n");
}

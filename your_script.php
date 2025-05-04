<?php

$channels = [
    '華視新聞' => 'https://www.youtube.com/@CtsTw',
    'Muse木棉花-闔家歡' => 'https://www.youtube.com/@Muse_Family',
    'Muse木棉花-TW' => 'https://www.youtube.com/@MuseTW/streams'
];

$results = [];

foreach ($channels as $name => $url) {
    $html = @file_get_contents($url);
    if (!$html) continue;

    // 找出直播影片的網址（簡單匹配 LIVE）
    if (preg_match('/"url":"(\/watch\?v=[^"]+)",.*?"isLive":true/', $html, $matches)) {
        $videoPath = stripslashes($matches[1]);
        $fullUrl = 'https://www.youtube.com' . $videoPath;
        $results[] = "$name,$fullUrl";
    }
}

// 寫入結果至文字檔
if (!empty($results)) {
    file_put_contents('live_streams.txt', implode(PHP_EOL, $results));
}
?>

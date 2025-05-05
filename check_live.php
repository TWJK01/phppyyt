<?php

$channels = [
    '華視新聞' => 'https://www.youtube.com/@CtsTw',
    'Muse木棉花-闔家歡' => 'https://www.youtube.com/@Muse_Family',
    'Muse木棉花-TW' => 'https://www.youtube.com/@MuseTW',
];

$liveUrls = [];

foreach ($channels as $name => $baseUrl) {
    $streamsUrl = $baseUrl . '/streams';

    $html = @file_get_contents($streamsUrl);
    if (!$html) {
        echo "無法抓取: $streamsUrl\n";
        continue;
    }

    // 搜尋直播中的影片 URL（含「LIVE NOW」標示）
    if (preg_match_all('/"url":"\/watch\?v=([a-zA-Z0-9_\-]{11})","title":\{"runs":\[\{"text":"(.*?)"\}\],"accessibility.*?"simpleText":"LIVE NOW"/', $html, $matches)) {
        foreach ($matches[1] as $index => $videoId) {
            $title = $matches[2][$index];
            $videoUrl = "https://www.youtube.com/watch?v=$videoId";
            $liveUrls[] = "$name: $videoUrl";
        }
    } else {
        echo "[$name] 沒有偵測到正在直播的影片。\n";
    }
}

// 寫入文字檔
file_put_contents('live_urls.txt', implode(PHP_EOL, $liveUrls) . PHP_EOL);
echo "已寫入 " . count($liveUrls) . " 筆直播連結至 live_urls.txt\n";

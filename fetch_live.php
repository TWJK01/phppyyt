<?php

date_default_timezone_set('Asia/Taipei');

$channels = [
    "Muse木棉花-TW" => "https://www.youtube.com/@MuseTW/streams",
    "Muse木棉花-闔家歡" => "https://www.youtube.com/@Muse_Family/streams"
];

$liveLinks = [];

foreach ($channels as $name => $url) {
    $html = fetchContent($url);

    if (preg_match_all('/"videoId":"(.*?)"/', $html, $matches)) {
        $videoId = $matches[1][0];
        $liveUrl = "https://www.youtube.com/watch?v=" . $videoId;
        $liveLinks[] = "$name: $liveUrl";
    } else {
        $liveLinks[] = "$name: 無法擷取直播連結";
    }
}

$timestamp = date("Y-m-d H:i:s");
$output = "更新時間：$timestamp\n" . implode(PHP_EOL, $liveLinks) . "\n\n";

file_put_contents("live_links.txt", $output);

function fetchContent($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

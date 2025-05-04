<?php

$channels = [
    "華視新聞" => "https://www.youtube.com/@CtsTw",
    "Muse木棉花-闔家歡" => "https://www.youtube.com/@Muse_Family",
    "Muse木棉花-TW" => "https://www.youtube.com/@MuseTW"
];

$liveUrls = [];

foreach ($channels as $name => $url) {
    $html = file_get_contents($url . "/streams");

    if (!$html) continue;

    // 用正規表達式找出直播影片網址
    if (preg_match_all('/"url":"(\/watch\?v=[^"]+)"/', $html, $matches)) {
        foreach ($matches[1] as $relativeUrl) {
            $fullUrl = "https://www.youtube.com" . stripslashes($relativeUrl);
            if (strpos($html, 'LIVE NOW') !== false || strpos($html, '直播') !== false) {
                $liveUrls[] = "$name: $fullUrl";
            }
        }
    }
}

// 輸出到 txt 檔案
file_put_contents("live_urls.txt", implode("\n", $liveUrls));

echo "Done. Found " . count($liveUrls) . " live(s).\n";

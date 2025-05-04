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

            // 檢查是否有 LIVE NOW 的標誌
            if (strpos($html, 'LIVE NOW') !== false || strpos($html, '直播') !== false) {
                $liveUrls[] = "$name: $fullUrl";  // 只將 LIVE 的影片網址加到清單
            }
        }
    }
}

// 如果有找到 LIVE 網址，將其寫入文字檔
if (count($liveUrls) > 0) {
    file_put_contents("live_urls.txt", implode("\n", $liveUrls));
} else {
    echo "目前沒有正在直播的影片。\n";
}

echo "Done. Found " . count($liveUrls) . " live(s).\n";

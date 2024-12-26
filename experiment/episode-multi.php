<?php
function createEpisodePage($episode)
{
    $title = $episode->title;
    $description = $episode->description;
    $audioUrl = $episode->audioUrl;

    $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <title>$title</title>
</head>
<body>
    <h1>$title</h1>
    <p>$description</p>
    <audio controls>
        <source src="$audioUrl" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio>
</body>
</html>
HTML;

    $filename = sanitizeFilename($title) . '.html';
    file_put_contents($filename, $html);
    echo "Created page for episode: $filename<br>";
}

function sanitizeFilename($filename)
{
    $filename = preg_replace("/[^a-zA-Z0-9\s]/", "", $filename);
    $filename = preg_replace("/\s+/", "-", $filename);
    $filename = strtolower($filename);
    return $filename;
}

$rssUrl = 'https://media.rss.com/saamannen/feed.xml'; // Replace with your podcast RSS feed URL

$xml = simplexml_load_file($rssUrl);

if ($xml === false) {
    echo 'Failed to load RSS feed.';
    exit;
}

foreach ($xml->channel->item as $item) {
    $episode = new stdClass();
    $episode->title = (string)$item->title;
    $episode->description = (string)$item->description;
    $episode->audioUrl = (string)$item->enclosure['url'];

    createEpisodePage($episode);
}
?>

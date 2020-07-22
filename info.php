<?php
// Address for the json status, this is default
// Icecast can only listen on one port so if you
// want localhost and external to work you will
// need two listeners.
$url = "http://localhost:8000/status-json.xsl";

// Get the json and decode it into an array
$json = file_get_contents($url);
$d = json_decode($json, true);

// Checks if there are multiple sources (ie; live, autodj)
// and if so, checks for source "1" (live in my case)
// and then uses it, otherwise source 0/only source
if (isset($d['icestats']['source']['0'])) {
	if (isset($d['icestats']['source']['1']['stream_start'])) {
		$details = $d['icestats']['source']['1'];
	} else {
		$details = $d['icestats']['source']['0'];
	}
} else {
	$details = $d['icestats']['source'];
}

// If there is an artist/track metadata, get it
// otherwise set to N/A
if (isset($details['title'])) {
	list($artist, $title) = explode(" - ", $details['title']);
} else {
	$title = "N/A";
	$artist = "N/A";
}

// Pack into array
$stream = array(
			"dj" => $details['server_name'],
			"desc" => $details['server_description'],
			"title" => $title,
			"artist" => $artist,
			"genre" => $details['genre'],
			"start_time" => $details['stream_start_iso8601'],
			"listn" => $details['listeners'],
			"peak" => $details['listener_peak']
		);
?>

<html>
	<head>
		<link rel="stylesheet" href="style.css" />
		<link href="https://fonts.googleapis.com/css?family=IBM+Plex+Mono" rel="stylesheet">
		<meta http-equiv="refresh" content="10" >
	</head>
	<body>
		<p class="detail">Track: <?= $stream['title'] ?></p>
		<p class="listeners">🎧 Current: <?= $stream['listn'] ?></p>
		<p class="listeners">📈 Peak: <?= $stream['peak'] ?></p>
		<p class="detail">Artist: <?= $stream['artist'] ?></p>
		<p class="detail">DJ: <?= $stream['dj'] ?></p>
		<p class="detail">Description: <?= $stream['desc'] ?></p>
	</body>
</html>
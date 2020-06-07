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
		<link href="https://fonts.googleapis.com/css?family=IBM+Plex+Mono" rel="stylesheet">
		<title>Anal Crickets Internet Radio</title>
		<link rel="stylesheet" href="style.css" />
		<link rel="apple-touch-icon" sizes="180x180" href="/favicons/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/favicons/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/favicons/favicon-16x16.png">
		<link rel="manifest" href="/favicons/site.webmanifest">
		<link rel="mask-icon" href="/favicons/safari-pinned-tab.svg" color="#5bbad5">
		<link rel="shortcut icon" href="/favicons/favicon.ico">
		<meta name="msapplication-TileColor" content="#da532c">
		<meta name="msapplication-config" content="/favicons/browserconfig.xml">
		<meta name="theme-color" content="#ffffff">
	</head>
	<body>
		<div class="container">
			<div class="box">
				<div class="details">
					<div class="header"><img class="icon" src="./analcricket.png" alt="poopy"/>Anal Crickets<a class="link" href="./live">/live</a></div> 
					<div class="space"></div>
					<p class="detail">Artist: <?= $stream['artist'] ?></p>
					<p class="listeners">🎧 Current: <?= $stream['listn'] ?></p>
					<p class="listeners">📈 Peak: <?= $stream['peak'] ?></p>
					<p class="detail">Track: <?= $stream['title'] ?></p>
					<p class="detail">DJ: <?= $stream['dj'] ?></p>
					<p class="detail">Description: <?= $stream['desc'] ?></p>
				</div>
				</div>
			</div>
		</div>
	</body>
</html>
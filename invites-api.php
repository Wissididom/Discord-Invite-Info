<?php
if (isset($_GET['invite'])) {
	$invite = $_GET['invite'];
	header('Content-Type: application/json');
	$ch = curl_init("https://discord.com/api/v8/invites/$invite?with_counts=true");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
	echo curl_exec($ch);
} else {
	http_response_code(400);
	echo '400 Bad Request';
}
?>

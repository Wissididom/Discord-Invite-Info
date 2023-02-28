<?php
$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
switch ($lang) {
	/*case "fr":
		//Französisch
		break;
	case "it":
		//Italienisch
		break;*/
	case "de":
		$invite = "Geben Sie hier den Discord Invite ein (Format: discord.com/invite/sdgsdgsg, discord.gg/sdgsdgsg, discord.gg/sdgsdgsg oder sdgsdgsg, macht nichts aus, wenn ein http:// oder https:// am Anfang steht):";
		$show = "Daten anzeigen";
		$copy = "Daten kopieren";
		$copiedSuccessfully = "Erfolgreich kopiert";
		$copyFailed = "Fehler beim Kopieren";
		$save = "Daten speichern";
		break;
	case "en":
		//Englisch
	default:
		$invite = "Please enter your Discord Invite here (Format: discord.com/invite/sdgsdgsg, discord.gg/sdgsdgsg, discord.gg/sdgsdgsg or sdgsdgsg, doesn't matter if there is a http:// or https:// in the beginning):";
		$show = "Show Data";
		$copy = "Copy Data";
		$copiedSuccessfully = "Copied successfully";
		$copyFailed = "Copy failed";
		$save = "Save Data";
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Discord-Invite-Info</title>
	</head>
	<body>
		<label for="invite"><?php echo $invite; ?></label>
		<div style="display: flex; flex-direction: row;">
			<input type="text" id="invite" name="invite" style="width:100%;" />
		</div>
		<p></p>
		<div style="display: flex; flex-direction: row;">
			<input type="button" onclick="show()" style="flex: 1;" value="<?php echo $show; ?>" />
			&nbsp;
			<input type="button" onclick="copy()" style="flex: 1;" value="<?php echo $copy; ?>" />
			&nbsp;
			<input type="button" onclick="save()" style="flex: 1;" value="<?php echo $save; ?>" />
		</div>
		<pre id="jsonOutput"></pre>
		<script>
			async function retrieveDiscordApi(inviteCode) {
				return fetch(`https://discord.com/api/v10/invites/${inviteCode}?with_counts=true`).then(res => {
					return res.json();
				}).then(json => {
					if (json.guild)
						json.guild.icon = `https://cdn.discordapp.com/icons/${json.guild.id}/${json.guild.icon}.webp`;
					if (json.inviter)
						json.inviter.avatar = `https://cdn.discordapp.com/avatars/${json.inviter.id}/${json.inviter.avatar}.webp`;
					return json;
				});
			}
			async function show() {
				try {
					let discordInvite = document.getElementById('invite').value;
					let inviteCode = discordInvite.indexOf('/') >= 0 ? discordInvite.substring(discordInvite.lastIndexOf('/') + 1) : discordInvite;
					let inviteData = await retrieveDiscordApi(inviteCode);
					document.getElementById('jsonOutput').innerHTML = JSON.stringify(inviteData, null, 4);
					console.log(`Successfully displayed the following in the pre:`, inviteData);
				} catch (err) {
					console.error('Failed to display: ', err);
				}
			}
			async function copy() {
				try {
					let discordInvite = document.getElementById('invite').value;
					let inviteCode = discordInvite.indexOf('/') >= 0 ? discordInvite.substring(discordInvite.lastIndexOf('/') + 1) : discordInvite;
					let inviteData = await retrieveDiscordApi(inviteCode);
					await navigator.clipboard.writeText(JSON.stringify(inviteData, null, 4));
					console.log(`Successfully copied the following to clipboard:`, inviteData);
					alert('<?php echo $copiedSuccessfully; ?>');
				} catch (err) {
					console.error('Failed to copy: ', err);
					alert('<?php echo $copyFailed; ?>');
				}
			}
			async function save() {
				try {
					let discordInvite = document.getElementById('invite').value;
					let inviteCode = discordInvite.indexOf('/') >= 0 ? discordInvite.substring(discordInvite.lastIndexOf('/') + 1) : discordInvite;
					let inviteData = await retrieveDiscordApi(inviteCode);
					let blob = new Blob([JSON.stringify(inviteData, null, 4)], {type: "application/json"});
					let a = document.createElement('a');
					a.download = `${inviteCode}.json`;
					a.href = URL.createObjectURL(blob);
					a.click();
					console.log(`Successfully copied the following to clipboard:`, inviteData);
				} catch (err) {
					console.error('Failed to copy: ', err);
				}
			}
		</script>
	</body>
</html>

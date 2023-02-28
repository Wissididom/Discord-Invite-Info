function sanitizeInvite(invite) {
	return invite.indexOf('/') >= 0 ? invite.substring(invite.lastIndexOf('/') + 1) : invite;
}

async function requestDiscordApi(inviteCode) {
	return fetch(`https://discord.com/api/v10/${inviteCode}?with_counts=true`).then(res => {
		return res.json();
	}).then(json => {
		if (json.guild)
			json.guild.icon = `https://cdn.discordapp.com/icons/${json.guild.id}/${json.guild.icon}.webp`;
		if (json.inviter)
			json.inviter.avatar = `https://cdn.discordapp.com/avatars/${json.inviter.id}/${json.inviter.avatar}.webp`;
		return json;
	});;
}

async function show() {
	try {
		let inviteCode = sanitizeInvite(document.getElementById('invite').value);
		let inviteData = await requestDiscordApi(inviteCode);
		document.getElementById('jsonOutput').innerHTML = JSON.stringify(inviteData, null, 4);
		console.log('Successfully displayed the following in the pre:', inviteData);
	} catch (err) {
		console.error('Failed to display:', err);
	}
}

async function copy(successMsg, errorMsg) {
	try {
		let inviteCode = sanitizeInvite(document.getElementById('invite').value);
		let inviteData = await requestDiscordApi(inviteCode);
		await navigator.clipboard.writeText(JSON.stringify(inviteData, null, 4));
		console.log('Successfully copied the following to clipboard:', inviteData);
		alert(successMsg);
	} catch (err) {
		console.error('Failed to copy:', err);
		alert(errorMsg);
	}
}

async function save() {
	try {
		let inviteCode = sanitizeInvite(document.getElementById('invite').value);
		let inviteData = await requestDiscordApi(inviteCode);
		let blob = new Blob([JSON.stringify(inviteData, null, 4)], {type: 'application/json'});
		let a = document.createElement('a');
		a.download = `${inviteCode}.json`;
		a.href = URL.createObjectURL(blob);
		a.click();
		console.log('Successfully saved the following to file:', inviteData);
	} catch (err) {
		console.error('Failed to save:', err);
	}
}

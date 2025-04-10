window.remoteUsers = {};

window.subscribe = async function(client, user, mediaType) {
  const uid = user.uid;

  await client.subscribe(user, mediaType);
  console.log("subscribe success");

  if (mediaType === 'video') {
    const player = document.createElement("div");
    player.id = `player-wrapper-${uid}`;
    player.innerHTML = `
      <p class="player-name">remoteUser(${uid})</p>
      <div id="player-${uid}" class="player"></div>
    `;
    document.getElementById("remote-playerlist").appendChild(player);

    user.videoTrack.play(`player-${uid}`);
  }

  if (mediaType === 'audio') {
    user.audioTrack.play();
  }
};

window.handleUserPublished = function(user, mediaType) {
  console.log('in user published lekhni', user.uid);
  window.remoteUsers[user.uid] = user;
  window.subscribe(client, user, mediaType);
};

window.handleUserUnpublished = function(user) {
  console.log("user UNpublished", user.uid);
  delete window.remoteUsers[user.uid];
  const el = document.getElementById(`player-wrapper-${user.uid}`);
  if (el) el.remove();
};

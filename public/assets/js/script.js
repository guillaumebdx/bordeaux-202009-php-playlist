let player;

function onYouTubePlayerAPIReady()
{
    player = new YT.Player('video', {
        events: {
            'onReady': onPlayerReady
        }
    });
}

function onPlayerReady(event)
{

    let playButton = document.getElementById("play-button");
    playButton.addEventListener("click", function () {
        player.playVideo();
    });

    let pauseButton = document.getElementById("pause-button");
    pauseButton.addEventListener("click", function () {
        player.pauseVideo();
    });

    let stopButton = document.getElementById("stop-button");
    stopButton.addEventListener("click", function () {
        player.stopVideo();
    });

}

// Inject YouTube API script
var tag = document.createElement('script');
tag.src = "https://www.youtube.com/player_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);


// LIKES //
const likes = document.getElementsByClassName('fa-heart');

for (let i = 0; i < likes.length; i++) {
    likes[i].addEventListener('click', (event) => {
        fetch('/like/add', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                'like': event.target.dataset.nblike,
                'trackId': event.target.dataset.trackid,
                })
        })
            .then(response => response.json())
            .then(data =>
                document.getElementById('likecounter-' + event.target.dataset.trackid).innerHTML = data.after,
                likes[i].classList.add('fas'),


            )

    })
}
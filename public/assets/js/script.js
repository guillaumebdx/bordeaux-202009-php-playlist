let ytplayerList;

function onPlayerReady(e)
{
    let video_data = e.target.getVideoData(),
        label = video_data.video_id + ':' + video_data.title;
    e.target.ulabel = label;
}

function onPlayerError(e)
{}

function onPlayerStateChange(e)
{

    let label = e.target.ulabel;
    if (e["data"] === YT.PlayerState.PLAYING) {
        pauseOthersYoutubes(e.target);
    }
    if (e["data"] == YT.PlayerState.PAUSED) {
    }

    if (e["data"] == YT.PlayerState.ENDED) {
    }


    if (e["data"] == YT.PlayerState.BUFFERING) {
        e.target.uBufferingCount ? ++e.target.uBufferingCount : e.target.uBufferingCount = 1;

        if ( YT.PlayerState.UNSTARTED ==  e.target.uLastPlayerState ) {
            pauseOthersYoutubes(e.target);
        }
    }

    if ( e.data != e.target.uLastPlayerState ) {
    }
}
function initYoutubePlayers()
{
    ytplayerList = null;
    ytplayerList = [];
    for (let e = document.getElementsByTagName("iframe"), x = e.length; x--;) {
        if (/youtube.com\/embed/.test(e[x].src)) {
            ytplayerList.push(initYoutubePlayer(e[x]));
        }
    }

}
function pauseOthersYoutubes( currentPlayer )
{
    if (!currentPlayer) {
        return;
    }
    for (let i = ytplayerList.length; i--;) {
        if ( ytplayerList[i] && (ytplayerList[i] != currentPlayer) ) {
            ytplayerList[i].pauseVideo();
        }
    }
}
//init a youtube iframe
function initYoutubePlayer(ytiframe)
{

    let ytp = new YT.Player(ytiframe, {
        events: {
            onStateChange: onPlayerStateChange,
            onError: onPlayerError,
            onReady: onPlayerReady
        }
    });
    ytiframe.ytp = ytp;
    return ytp;
}
function onYouTubeIframeAPIReady()
{
    initYoutubePlayers();
}
let tag = document.createElement('script');
tag.src = "https://www.youtube.com/iframe_api";
let firstScriptTag = document.getElementsByTagName('script')[0];
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
        .then(
            data =>
            document.getElementById('likecounter-' + event.target.dataset.trackid).innerHTML = data.after,
            likes[i].classList.add('fas'),
        )

    })
}
// Data Picker Initialization
$('.datepicker').datepicker({ min: new Date(2015,3,20)
})
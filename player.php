<?php
/**
 * Simple Video Player
 *
 * This function generates a simple video player using Video.js with a playlist feature.
 * It scans a specified directory for MP4 files and creates a playlist from them.
 *
 * @see https://videojs.com/blog/video-js-5-s-fluid-mode-and-playlist-picker/
 * @see https://www.nlm.nih.gov/scripts/videojs/examples/playlisth.html
 *
 * @param string $videoPath The path to the directory containing video files.
 * @param string|null $documentRoot The document root of the server. Defaults to the server's document root.
 * @param string|null $serverName The server name. Defaults to the server's name.
 */

function simpleVideoPlayer(
    $videoPath,
    $documentRoot = null,
    $serverName = null
) {
    $documentRoot = null === $documentRoot ? $_SERVER['DOCUMENT_ROOT'] : $documentRoot;
    $serverName = null === $serverName ? $_SERVER['SERVER_NAME'] : $serverName;

    $result = array();
    $videoDir = $documentRoot . '/' . $videoPath;

    foreach (glob($videoDir . '/*.mp4') as $file) {
        $fileName = rawurldecode(basename($file, '.mp4'));
        // Video.js playlist format
        $result[] = array(
            'sources' => array(
                array(
                    'src' => sprintf('//%s/%s/%s.mp4', $serverName, $videoPath, $fileName),
                    'type' => 'video/mp4'
                ),
            ),
            'name' => pathinfo($file, PATHINFO_FILENAME),
        );
    }
    ?>
<!DOCTYPE html>
<html lang="ja-jp">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Simple Video Player</title>

    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/normalize.css@8.0.1/normalize.min.css">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/video.js@8.23.3/dist/video-js.min.css">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/videojs-playlist-ui@5.0.0/dist/videojs-playlist-ui.min.css">

    <style>
      .main-preview-player {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
      }
      .video-js,
      .playlist-container {
        position: relative;
        min-width: 300px;
        min-height: 150px;
        height: 0;
      }
      .video-js {
        flex: 3 1 70%;
      }
      .playlist-container {
        flex: 1 1 30%;
      }
      .vjs-playlist {
        margin: 0;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
      }
    </style>
  </head>
  <body>
    <section class="main-preview-player">
      <video id="preview-player" class="video-js" controls preload="auto" crossorigin="anonymous">
        <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
      </video>

      <div class="playlist-container preview-player-dimensions">
        <ol class="vjs-playlist"></ol>
      </div>
    </section>

    <script src="//cdn.jsdelivr.net/npm/video.js@8.23.3/dist/video.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/videojs-playlist@5.2.0/dist/videojs-playlist.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/videojs-playlist-ui@5.0.0/dist/videojs-playlist-ui.min.js"></script>

    <script>
      (function (window, document) {
        var player = videojs('preview-player');
        player.playlist(<?php echo json_encode($result); ?>);
        player.playlistUi();


        var adjustSize = function () {
          var height = window.innerHeight;
          document.querySelector('.main-preview-player').style.height = height + 'px';
          document.querySelector('#preview-player').style.height = height + 'px';
          document.querySelector('.playlist-container').style.height = height + 'px';
          setTimeout(adjustSize, 500);
        };
        adjustSize();
      })(window, window.document);
    </script>
  </body>
</html>


    <?php
}

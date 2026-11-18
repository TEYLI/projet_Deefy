<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../vendor/autoload.php';

session_start();

\Iutnc\Deefy\repository\DeefyRepository::setConfig(__DIR__ . '/../db.config.ini');

$dispatcher = new \Iutnc\Deefy\dispatch\Dispatcher();

$dispatcher
    ->addRoute('default', \Iutnc\Deefy\action\DefaultAction::class)
    ->addRoute('playlists', \Iutnc\Deefy\action\DisplayPlaylistAction::class)
    ->addRoute('display-playlist', \Iutnc\Deefy\action\DisplayPlaylistAction::class)
    ->addRoute('current-playlist', \Iutnc\Deefy\action\DisplayCurrentPlaylistAction::class)
    ->addRoute('add-playlist', \Iutnc\Deefy\action\AddPlaylistAction::class)
    ->addRoute('add-track-podcast', \Iutnc\Deefy\action\AddPodcastTrackAction::class)
    ->addRoute('add-track-album', \Iutnc\Deefy\action\AddAlbumTrackAction::class)
    ->addRoute('select-playlist', \Iutnc\Deefy\action\SelectPlaylistAction::class);

$dispatcher->run();
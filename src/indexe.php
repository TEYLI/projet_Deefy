<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../vendor/autoload.php';

session_start();

\Iutnc\Deefy\Repository\DeefyRepository::setConfig(__DIR__ . '/../db.config.ini');

$dispatcher = new \Iutnc\Deefy\dispatch\Dispatcher();

$dispatcher
    ->addRoute('default', \Iutnc\Deefy\Action\DefaultAction::class)
    ->addRoute('playlists', \Iutnc\Deefy\Action\DisplayPlaylistAction::class)
    ->addRoute('display-playlist', \Iutnc\Deefy\Action\DisplayPlaylistAction::class)
    ->addRoute('current-playlist', \Iutnc\Deefy\Action\DisplayCurrentPlaylistAction::class)
    ->addRoute('add-playlist', \Iutnc\Deefy\Action\AddPlaylistAction::class)
    ->addRoute('add-track-podcast', \Iutnc\Deefy\Action\AddPodcastTrackAction::class)
    ->addRoute('add-track-album', \Iutnc\Deefy\Action\AddAlbumTrackAction::class);

$dispatcher->run();
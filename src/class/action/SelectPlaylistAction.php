<?php

namespace Iutnc\Deefy\action;

use Iutnc\Deefy\audio\lists\Playlists;
use Iutnc\Deefy\repository\DeefyRepository;

class SelectPlaylistAction extends Action
{
    public function __construct()
    {
        parent::__construct();
    }

    public function execute(): string
    {
        $repo = DeefyRepository::getInstance();

        $playlist = $repo->findPlaylistById($_GET['id']);
        $_SESSION['playlist'] = $playlist;

        header("Location: ?action=current-playlist");
        exit();

        return "";
    }
}
<?php

namespace Iutnc\Deefy\Action;

use Iutnc\Deefy\Audio\Lists\Playlists;
use Iutnc\Deefy\Repository\DeefyRepository;

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
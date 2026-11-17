<?php

namespace Iutnc\Deefy\Action;

use Iutnc\Deefy\Audio\Lists\Playlists;
use Iutnc\Deefy\Render\AudioListRenderer;

class DisplayCurrentPlaylistAction extends Action
{
    public function __construct()
    {
        parent::__construct();
    }

    public function execute(): string
    {
        if (!isset($_SESSION['playlist']) || !($_SESSION['playlist'] instanceof Playlists)) {
            return <<<HTML
                <p>Aucune playlist courante en session.</p>
                <a href="?action=add-playlist">Créer une playlist</a> |
                <a href="?action=playlists">Voir les playlists</a>
            HTML;
        }

        $renderer = new AudioListRenderer($_SESSION['playlist']);
        $html = $renderer->render(1);

        return <<<HTML
            $html
            <a href="?action=add-track-podcast">Ajouter un podcast</a> |
            <a href="?action=add-track-album">Ajouter une piste d'album</a>
        HTML;
    }
}
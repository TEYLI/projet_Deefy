<?php

namespace Iutnc\Deefy\action;

use Iutnc\Deefy\audio\lists\Playlists;
use Iutnc\Deefy\render\AudioListRenderer;

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
                <div class="alert alert-warning">Aucune playlist courante en session.</div>
                <div class="d-flex gap-2">
                    <a class="btn btn-primary" href="?action=add-playlist">Créer une playlist</a>
                    <a class="btn btn-outline-secondary" href="?action=playlists">Voir les playlists</a>
                </div>
            HTML;
        }

        $renderer = new AudioListRenderer($_SESSION['playlist']);
        $html = $renderer->render(2);

        return <<<HTML
            <div class="card shadow-sm mb-3">
                <div class="card-body">$html</div>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a class="btn btn-primary" href="?action=add-track-podcast">Ajouter un podcast</a>
                <a class="btn btn-outline-primary" href="?action=add-track-album">Ajouter une piste d'album</a>
            </div>
        HTML;
    }
}
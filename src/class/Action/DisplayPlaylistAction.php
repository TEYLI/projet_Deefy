<?php

namespace Iutnc\Deefy\Action;

use Iutnc\Deefy\Repository\DeefyRepository;
use Iutnc\Deefy\Render\AudioListRenderer;

class DisplayPlaylistAction extends Action
{
    public function __construct()
    {
        parent::__construct();
    }

    public function execute(): string
    {
        if (!isset($_GET['id'])) {
            return "<p>Identifiant de playlist manquant.</p>";
        }

        $id = (int)$_GET['id'];
        $repo = DeefyRepository::getInstance();
        $playlist = $repo->findPlaylistById($id);

        if ($playlist === null) {
            return "<p>Playlist introuvable.</p>";
        }

        $_SESSION['playlist'] = $playlist;

        $renderer = new AudioListRenderer($playlist);
        $html = $renderer->render(1);

        return <<<HTML
            $html
            <a href="?action=add-track-podcast">Ajouter un podcast</a> |
            <a href="?action=add-track-album">Ajouter une piste d'album</a>
        HTML;
    }
}
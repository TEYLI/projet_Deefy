<?php

namespace Iutnc\Deefy\Action;

use Iutnc\Deefy\Audio\Lists\Playlists;
use Iutnc\Deefy\Repository\DeefyRepository;
use Iutnc\Deefy\Render\AudioListRenderer;

class AddPlaylistAction extends Action
{
    public function __construct()
    {
        parent::__construct();
    }

    public function execute(): string
    {
        if ($this->http_method === "GET") {
            return <<<HTML
                <form method="post" action="?action=add-playlist">
                    <label>Titre Playlist : </label>
                    <input type="text" name="addPlaylist" required>
                    <button type="submit">Créer</button>
                </form>
            HTML;
        }

        if (!empty($_POST["addPlaylist"])) {
            $titre = filter_var($_POST["addPlaylist"], FILTER_SANITIZE_SPECIAL_CHARS);
            $playlist = new Playlists($titre);

            $repo = DeefyRepository::getInstance();
            $playlist = $repo->saveEmptyPlaylist($playlist);

            $_SESSION["playlist"] = $playlist;

            $audioRender = new AudioListRenderer($playlist);
            $html = $audioRender->render(1);

            return <<<HTML
                $html
                <a href="?action=add-track-podcast">Ajouter un podcast</a> |
                <a href="?action=add-track-album">Ajouter une piste</a>
            HTML;
        }

        return '<p>Veuillez saisir un nom de playlist.</p><a href="?action=add-playlist">Retour</a>';
    }
}
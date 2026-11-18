<?php

namespace Iutnc\Deefy\action;

use Iutnc\Deefy\audio\lists\Playlists;
use Iutnc\Deefy\repository\DeefyRepository;
use Iutnc\Deefy\render\AudioListRenderer;

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
                <div class="row justify-content-center">
                    <div class="col-12 col-md-8 col-lg-6">
                        <div class="card shadow-sm">
                            <div class="card-body p-4">
                                <h1 class="h3 mb-4">Créer une playlist</h1>
                                <form method="post" action="?action=add-playlist">
                                    <div class="mb-3">
                                        <label class="form-label" for="playlist-name">Titre de la playlist</label>
                                        <input class="form-control" id="playlist-name" type="text" name="addPlaylist" required>
                                    </div>
                                    <button class="btn btn-primary" type="submit">Créer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
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
<?php

namespace Iutnc\Deefy\action;

use Iutnc\Deefy\repository\DeefyRepository;

class AddAlbumTrackAction extends Action
{
    public function __construct()
    {
        parent::__construct();
    }

    public function execute(): string
    {
        $info = "";
        $res = "";

        if ($this->http_method === "GET") {
            $res = <<<HTML
                <div class="row justify-content-center">
                    <div class="col-12 col-lg-8">
                        <div class="card shadow-sm">
                            <div class="card-body p-4">
                                <h1 class="h3 mb-4">Ajouter une piste d'album</h1>
                                <form method="post" action="?action=add-track-album" enctype="multipart/form-data">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label" for="album-title">Titre de la piste</label>
                                            <input class="form-control" id="album-title" type="text" name="titre" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="album-file">Fichier MP3</label>
                                            <input class="form-control" id="album-file" type="file" name="mp3" accept=".mp3,audio/mpeg" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="album-name">Album</label>
                                            <input class="form-control" id="album-name" type="text" name="album" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label" for="album-year">Année</label>
                                            <input class="form-control" id="album-year" type="number" name="année" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label" for="album-duration">Durée (s)</label>
                                            <input class="form-control" id="album-duration" type="number" name="temps" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="album-genre">Genre</label>
                                            <input class="form-control" id="album-genre" type="text" name="genre" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="album-author">Auteur</label>
                                            <input class="form-control" id="album-author" type="text" name="auteur" required>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary mt-4" type="submit">Ajouter</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
              HTML;
        } else {
            if (!isset($_SESSION["playlist"])) {
                return '<div class="alert alert-warning">Aucune playlist courante. <a href="?action=add-playlist">Créer une playlist</a></div>';
            }

            $titre = filter_var($_POST["titre"], FILTER_SANITIZE_SPECIAL_CHARS);
            $album = filter_var($_POST["album"], FILTER_SANITIZE_SPECIAL_CHARS);
            $duree = (int)filter_var($_POST["temps"], FILTER_SANITIZE_NUMBER_INT);
            $auteur = filter_var($_POST["auteur"], FILTER_SANITIZE_SPECIAL_CHARS);
            $genre = filter_var($_POST["genre"], FILTER_SANITIZE_SPECIAL_CHARS);
            $annee = (int)filter_var($_POST["année"], FILTER_SANITIZE_NUMBER_INT);

            if (
                isset($_FILES["mp3"]) &&
                $_FILES["mp3"]["error"] === UPLOAD_ERR_OK &&
                str_ends_with(strtolower($_FILES["mp3"]["name"]), ".mp3") &&
                $_FILES["mp3"]["type"] === "audio/mpeg"
            ) {
                $mp3 = $_FILES["mp3"]["tmp_name"];
                $filename = uniqid();

                $destination = dirname(__DIR__, 3) . "/audio/" . $filename . ".mp3";
                $src = "audio/" . $filename . ".mp3";

                if (!move_uploaded_file($mp3, $destination)) {
                    return '<div class="alert alert-danger">Téléchargement non valide : vérifiez les droits du dossier audio/.</div>';
                }
            } else {
                return '<div class="alert alert-danger">Le fichier envoyé n\'est pas un fichier .mp3 valide.</div>';
            }

            $playlist = $_SESSION["playlist"];
            $numeroPiste = $playlist->nb_pistes + 1;

            $piste = new \Iutnc\Deefy\audio\track\AlbumTrack($titre, $src, $album, $numeroPiste);
            $piste->duree = $duree;
            $piste->genre = $genre;
            $piste->artiste = $auteur;
            $piste->annee = $annee;

            $playlist->add_piste($piste);

            $repository = DeefyRepository::getInstance();
            $repository->savePiste($piste);
            $repository->addTrackToPlaylist(
                $playlist->getID(),
                $piste->getID(),
                $playlist->nb_pistes
            );

            $info = '<div class="alert alert-success d-flex align-items-center gap-2" role="status"><span class="fw-bold">✓</span><span>Téléchargement effectué avec succès.</span></div>';

            $html = "";
            foreach ($playlist->liste_pistes as $value) {
                if ($value instanceof \Iutnc\Deefy\audio\track\AlbumTrack) {
                    $trackRender = new \Iutnc\Deefy\render\AlbumTrackRender($value);
                } elseif ($value instanceof \Iutnc\Deefy\audio\track\PodcastTrack) {
                    $trackRender = new \Iutnc\Deefy\render\PodcastRender($value);
                }
                $html .= $trackRender->render(2);
            }

            $res = <<<HTML
                $info
                $html
                <div class="d-flex flex-wrap gap-2 mt-3">
                    <a class="btn btn-primary" href="?action=add-track-podcast">Ajouter encore un podcast</a>
                    <a class="btn btn-outline-primary" href="?action=add-track-album">Ajouter encore une piste d'album</a>
                </div>
            HTML;
        }

        return $res;
    }
}
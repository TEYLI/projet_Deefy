<?php

namespace Iutnc\Deefy\action;

use Iutnc\Deefy\repository\DeefyRepository;

class AddPodcastTrackAction extends Action
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
                                <h1 class="h3 mb-4">Ajouter un podcast</h1>
                                <form method="post" action="?action=add-track-podcast" enctype="multipart/form-data">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label" for="podcast-title">Titre</label>
                                            <input class="form-control" id="podcast-title" type="text" name="titre" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="podcast-file">Fichier MP3</label>
                                            <input class="form-control" id="podcast-file" type="file" name="mp3" accept=".mp3,audio/mpeg" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="podcast-date">Date</label>
                                            <input class="form-control" id="podcast-date" type="date" name="date" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="podcast-duration">Durée (s)</label>
                                            <input class="form-control" id="podcast-duration" type="number" name="temps" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="podcast-genre">Genre</label>
                                            <input class="form-control" id="podcast-genre" type="text" name="genre" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="podcast-author">Auteur</label>
                                            <input class="form-control" id="podcast-author" type="text" name="auteur" required>
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
            foreach (["titre", "temps", "auteur", "genre", "date"] as $field) {
                if (!isset($_POST[$field]) || $_POST[$field] === "") {
                    return '<div class="alert alert-danger">Veuillez remplir tous les champs du formulaire, y compris l’auteur.</div>';
                }
            }

            //values input
            $titre = filter_var($_POST["titre"], FILTER_SANITIZE_SPECIAL_CHARS);
            $duree = filter_var($_POST["temps"], FILTER_SANITIZE_NUMBER_INT);
            $auteur = filter_var($_POST["auteur"], FILTER_SANITIZE_SPECIAL_CHARS);
            $genre = filter_var($_POST["genre"], FILTER_SANITIZE_SPECIAL_CHARS);
            $date = filter_var($_POST["date"], FILTER_SANITIZE_SPECIAL_CHARS);

            if (!isset($_SESSION["playlist"])) {
                return '<div class="alert alert-warning">Aucune playlist courante. <a href="?action=add-playlist">Créer une playlist</a></div>';
            }

            //upload mp3
            $mp3 = "";
            $destination = "";

            if (substr($_FILES["mp3"]["name"], -4) === ".mp3" && $_FILES["mp3"]["type"] === "audio/mpeg") {
                $mp3 = $_FILES["mp3"]["tmp_name"];
                $filename = uniqid();

                $destination = dirname(__DIR__) . "/audio/" . $filename . ".mp3";
                $src = $filename . ".mp3";

                if (!move_uploaded_file($mp3, $destination)) {
                    return "<p> téléchargement non valide</p>";
                }

            } else {

                return "<p>" . $_FILES["mp3"]["tmp_name"] . "n'est pas un fichier .mp3</p>";

            }



            if ((isset($_SESSION["playlist"]))) {


                $piste = new \Iutnc\Deefy\audio\track\PodcastTrack($date, $titre, $src);
                $piste->duree = $duree;
                $piste->genre = $genre;
                $piste->artiste = $auteur;
                $playlist = $_SESSION["playlist"];
                $repository = DeefyRepository::getInstance();
                $repository->savePiste($piste);
                $repository->addTrackToPlaylist(
                    $playlist->getID(),
                    $piste->getID(),
                    $playlist->nb_pistes
                );
                $playlist->add_piste($piste);
                $info = '<div class="alert alert-success d-flex align-items-center gap-2" role="status"><span class="fw-bold">✓</span><span>Téléchargement effectué avec succès.</span></div>';



                $html = "";

                foreach ($_SESSION["playlist"]->liste_pistes as $value) {

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


            } else {
                $res = <<<HTML
                    $info
                    <a class="btn btn-primary" href="?action=add-playlist">Créer une playlist</a>
                HTML;
            }

        }

        return $res;

    }
}

<?php

namespace Iutnc\Deefy\Action;

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
            //values input
            $titre = filter_var($_POST["titre"], FILTER_SANITIZE_SPECIAL_CHARS);
            $album = filter_var($_POST["album"], FILTER_SANITIZE_SPECIAL_CHARS);
            $duree = filter_var($_POST["temps"], FILTER_SANITIZE_NUMBER_INT);
            $auteur = filter_var($_POST["auteur"], FILTER_SANITIZE_SPECIAL_CHARS);
            $genre = filter_var($_POST["genre"], FILTER_SANITIZE_SPECIAL_CHARS);
            $nomPlaylist = filter_var($_POST["nomPlaylist"], FILTER_SANITIZE_SPECIAL_CHARS);
            $annee = filter_var($_POST["année"], FILTER_SANITIZE_NUMBER_INT);



            //upload mp3
            $mp3 = "";
            $destination = "";

            if (substr($_FILES["mp3"]["name"], -4) === ".mp3" && $_FILES["mp3"]["type"] === "audio/mpeg") {
                $mp3 = $_FILES["mp3"]["tmp_name"];
                $filename = uniqid();



                $destination = dirname(__DIR__) . "/Audio/" . $filename . ".mp3";
                $src = "/DevWeb-PHP/Deefy/src/class/Audio/" . $filename . ".mp3";




                if (move_uploaded_file($mp3, $destination)) {

                    if (isset($_SESSION["playlist"])) {
                        print "<p> téléchargement avec succès </p>";
                    } else {
                        print "<p> la playlist " . $nomPlaylist . " n'existe pas <p>";
                    }



                } else {
                    return "<p> téléchargement non valide</p>";
                }

            } else {

                return "<p>" . $_FILES["mp3"]["tmp_name"] . "n'est pas un fichier .mp3</p>";

            }



            if ((isset($_SESSION["playlist"]))) {


                $piste = new \Iutnc\Deefy\Audio\Track\AlbumTrack($titre, $src, $album, count($_SESSION["playlist"]->liste_pistes));
                $piste->duree = $duree;
                $piste->genre = $genre;
                $piste->artiste = $auteur;
                $piste->annee = $annee;
                $_SESSION["playlist"]->add_piste($piste);



                $html = "";

                foreach ($_SESSION["playlist"]->liste_pistes as $value) {

                    if ($value instanceof \Iutnc\Deefy\Audio\Track\AlbumTrack) {

                        $trackRender = new \Iutnc\Deefy\Render\AlbumTrackRender($value);

                    } elseif ($value instanceof \Iutnc\Deefy\Audio\Track\PodcastTrack) {

                        $trackRender = new \Iutnc\Deefy\Render\PodcastRender($value);

                    }

                    $html .= $trackRender->render(2);
                }


                $res = <<<HTML
                    $html
                    <a href="?action=add-track-podcast">Ajouter encore un podcast</a>
                    <a href="?action=add-track-album">Ajouter encore une piste</a>
                HTML;


            } else {
                $res = <<<HTML
                    <a href="?action=add-playlist">creer une playlist</a>
                HTML;
            }

        }

        return $res;

    }
}

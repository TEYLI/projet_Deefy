<?php

namespace Iutnc\Deefy\action;

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
            $duree = filter_var($_POST["temps"], FILTER_SANITIZE_NUMBER_INT);
            $auteur = filter_var($_POST["auteur"], FILTER_SANITIZE_SPECIAL_CHARS);
            $genre = filter_var($_POST["genre"], FILTER_SANITIZE_SPECIAL_CHARS);
            $nomPlaylist = filter_var($_POST["nomPlaylist"], FILTER_SANITIZE_SPECIAL_CHARS);
            $date = filter_var($_POST["date"], FILTER_SANITIZE_SPECIAL_CHARS);


            //upload mp3
            $mp3 = "";
            $destination = "";

            if (substr($_FILES["mp3"]["name"], -4) === ".mp3" && $_FILES["mp3"]["type"] === "audio/mpeg") {
                $mp3 = $_FILES["mp3"]["tmp_name"];
                $filename = uniqid();



                $destination = dirname(__DIR__) . "/audio/" . $filename . ".mp3";
                $src = "/DevWeb-PHP/Deefy/src/class/audio/" . $filename . ".mp3";




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


                $piste = new \Iutnc\Deefy\audio\track\PodcastTrack($date, $titre, $src);
                $piste->duree = $duree;
                $piste->genre = $genre;
                $piste->artiste = $auteur;
                $_SESSION["playlist"]->add_piste($piste);



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

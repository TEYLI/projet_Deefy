<?php

namespace Iutnc\Deefy\Action;

use Iutnc\Deefy\Audio\Track\AlbumTrack;
use Iutnc\Deefy\Repository\DeefyRepository;
use Iutnc\Deefy\Render\AudioListRenderer;

class AddAlbumTrackAction extends Action
{
    public function __construct()
    {
        parent::__construct();
    }

    public function execute(): string
    {
        if (!isset($_SESSION["playlist"])) {
            return <<<HTML
                <p>Aucune playlist courante sélectionnée.</p>
                <a href="?action=add-playlist">Créer une playlist</a> |
                <a href="?action=playlists">Choisir une playlist existante</a>
            HTML;
        }

        if ($this->http_method === "GET") {
            $nomPlaylist = htmlspecialchars($_SESSION["playlist"]->nom);
            return <<<HTML
                <h3>Ajouter une piste d'album à la playlist « $nomPlaylist »</h3>
                <form method="post" action="?action=add-track-album" enctype="multipart/form-data">
                    <label>Titre piste : <input type="text" name="titre" required></label><br>
                    <label>Fichier mp3 : <input type="file" name="mp3" accept=".mp3,audio/mpeg" required></label><br>
                    <label>Album : <input type="text" name="album" required></label><br>
                    <label>Année : <input type="number" name="annee" required></label><br>
                    <label>Durée (en s) : <input type="number" name="temps" min="1" required></label><br>
                    <label>Genre : <input type="text" name="genre" required></label><br>
                    <label>Auteur : <input type="text" name="auteur" required></label><br>
                    <button type="submit">Ajouter la piste</button>
                </form>
            HTML;
        }

        $titre = filter_var($_POST["titre"] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $album = filter_var($_POST["album"] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $duree = (int)filter_var($_POST["temps"] ?? 0, FILTER_SANITIZE_NUMBER_INT);
        $auteur = filter_var($_POST["auteur"] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $genre = filter_var($_POST["genre"] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $annee = (int)filter_var($_POST["annee"] ?? 0, FILTER_SANITIZE_NUMBER_INT);

        if (!isset($_FILES["mp3"]) || $_FILES["mp3"]["error"] !== UPLOAD_ERR_OK) {
            return "<p>Erreur lors du téléchargement du fichier.</p>";
        }

        $nomOrigine = $_FILES["mp3"]["name"];
        if (!str_ends_with(strtolower($nomOrigine), ".mp3") || $_FILES["mp3"]["type"] !== "audio/mpeg") {
            return "<p>Erreur : le fichier envoyé n'est pas un .mp3 valide.</p>";
        }

        $filename = uniqid('album_') . ".mp3";
        $destination = "audio/" . $filename;

        if (!move_uploaded_file($_FILES["mp3"]["tmp_name"], $destination)) {
            return "<p>Téléchargement non valide : impossible de déplacer le fichier dans le dossier audio/.</p>";
        }

        $playlist = $_SESSION["playlist"];
        $numeroPiste = $playlist->nb_pistes + 1;

        $piste = new AlbumTrack($titre, $destination, $album, $numeroPiste);
        $piste->duree = $duree;
        $piste->genre = $genre;
        $piste->artiste = $auteur;
        $piste->annee = $annee;

        $playlist->add_piste($piste);

        $repo = DeefyRepository::getInstance();
        $repo->savePiste($piste);
        $repo->addTrackToPlaylist($playlist->getID(), $piste->getID(), $numeroPiste);

        $renderer = new AudioListRenderer($playlist);
        $html = $renderer->render(1);

        return <<<HTML
            <p>Piste d'album ajoutée avec succès !</p>
            $html
            <a href="?action=add-track-podcast">Ajouter un podcast</a> |
            <a href="?action=add-track-album">Ajouter encore une piste d'album</a>
        HTML;
    }
}
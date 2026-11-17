<?php

namespace Iutnc\Deefy\Action;

use Iutnc\Deefy\Audio\Track\PodcastTrack;
use Iutnc\Deefy\Repository\DeefyRepository;
use Iutnc\Deefy\Render\AudioListRenderer;

class AddPodcastTrackAction extends Action
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
                <h3>Ajouter un podcast à la playlist « $nomPlaylist »</h3>
                <form method="post" action="?action=add-track-podcast" enctype="multipart/form-data">
                    <label>Titre piste : <input type="text" name="titre" required></label><br>
                    <label>Fichier mp3 : <input type="file" name="mp3" accept=".mp3,audio/mpeg" required></label><br>
                    <label>Date : <input type="date" name="date" required></label><br>
                    <label>Durée (en s) : <input type="number" name="temps" min="1" required></label><br>
                    <label>Genre : <input type="text" name="genre" required></label><br>
                    <label>Auteur : <input type="text" name="auteur" required></label><br>
                    <button type="submit">Ajouter le podcast</button>
                </form>
            HTML;
        }

        $titre = filter_var($_POST["titre"] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $duree = (int)filter_var($_POST["temps"] ?? 0, FILTER_SANITIZE_NUMBER_INT);
        $auteur = filter_var($_POST["auteur"] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $genre = filter_var($_POST["genre"] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $date = filter_var($_POST["date"] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);

        if (!isset($_FILES["mp3"]) || $_FILES["mp3"]["error"] !== UPLOAD_ERR_OK) {
            return "<p>Erreur lors du téléchargement du fichier.</p>";
        }

        $nomOrigine = $_FILES["mp3"]["name"];
        if (!str_ends_with(strtolower($nomOrigine), ".mp3") || $_FILES["mp3"]["type"] !== "audio/mpeg") {
            return "<p>Erreur : le fichier envoyé n'est pas un .mp3 valide.</p>";
        }

        $filename = uniqid('podcast_') . ".mp3";
        $destination = "audio/" . $filename;

        if (!move_uploaded_file($_FILES["mp3"]["tmp_name"], $destination)) {
            return "<p>Téléchargement non valide : impossible de déplacer le fichier dans le dossier audio/.</p>";
        }

        $piste = new PodcastTrack($date, $titre, $destination);
        $piste->duree = $duree;
        $piste->genre = $genre;
        $piste->artiste = $auteur;

        $playlist = $_SESSION["playlist"];
        $playlist->add_piste($piste);

        $repo = DeefyRepository::getInstance();
        $repo->savePiste($piste);
        $repo->addTrackToPlaylist($playlist->getID(), $piste->getID(), $playlist->nb_pistes);

        $renderer = new AudioListRenderer($playlist);
        $html = $renderer->render(1);

        return <<<HTML
            <p>Podcast ajouté avec succès !</p>
            $html
            <a href="?action=add-track-podcast">Ajouter encore un podcast</a> |
            <a href="?action=add-track-album">Ajouter une piste d'album</a>
        HTML;
    }
}
<?php

namespace Iutnc\Deefy\Action;

use Iutnc\Deefy\Repository\DeefyRepository;

class DisplayAllPlaylistsAction extends Action
{
    public function __construct()
    {
        parent::__construct();
    }

    public function execute(): string
    {
        $repo = DeefyRepository::getInstance();
        $playlists = $repo->get_all_playlist();

        if (empty($playlists)) {
            return '<p>Aucune playlist enregistrée. <a href="?action=add-playlist">Créer une playlist</a></p>';
        }

        $html = "<h3>Mes playlists</h3><ul>";
        foreach ($playlists as $pl) {
            $nom = htmlspecialchars($pl->nom);
            $id = $pl->getID();
            $html .= "<li><a href='?action=display-playlist&id=$id'>$nom</a></li>";
        }
        $html .= "</ul>";

        return $html;
    }
}
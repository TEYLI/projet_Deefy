<?php

namespace Iutnc\Deefy\Action;


use \Iutnc\Deefy\Repository\DeefyRepository;
use \Iutnc\Deefy\Render\AudioListRenderer;
class DisplayPlaylistAction extends Action
{
    public function __construct()
    {
        parent::__construct();
    }

    public function execute(): string
    {


        

        $repo = DeefyRepository::getInstance();

        $res = "";

        $tab_playlist = $repo->get_all_playlist();

        if (empty($tab_playlist)) {
            return '<p>Aucune playlist enregistrée. <a href="?action=add-playlist">Créer une playlist</a></p>';
        }

        foreach ($tab_playlist as $key => $value) {
            $render = new AudioListRenderer($value);
            $id = $value->getID();
            $res .= <<<HTML
                <a href="?action=select-playlist&id=$id">{$render->render(1)} </a>
            HTML;
        }



        return $res;

    }
}
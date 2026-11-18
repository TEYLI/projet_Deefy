<?php

namespace Iutnc\Deefy\action;


use \Iutnc\Deefy\repository\DeefyRepository;
use \Iutnc\Deefy\render\AudioListRenderer;
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
            return '<div class="alert alert-info d-flex justify-content-between align-items-center">Aucune playlist enregistrée.<a class="btn btn-primary btn-sm" href="?action=add-playlist">Créer une playlist</a></div>';
        }

        $res .= '<div class="row g-3">';
        foreach ($tab_playlist as $key => $value) {
            $render = new AudioListRenderer($value);
            $id = $value->getID();
            $res .= <<<HTML
                <div class="col-12 col-md-6 col-lg-4">
                    <a class="text-decoration-none text-dark" href="?action=select-playlist&id=$id">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">{$render->render(1)}</div>
                        </div>
                    </a>
                </div>
            HTML;
        }
        $res .= '</div>';



        return $res;

    }
}
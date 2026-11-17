<?php
namespace Iutnc\Deefy\Action;

use \Iutnc\Deefy\repository\DeefyRepository;
use \Iutnc\Deefy\Render\AudioListRenderer;
class DisplayPlaylistAction extends Action
{

    public function __construct()
    {
        parent::__construct();
    }


    public function execute(): string
    {

        
        $path = __DIR__ . '/../repository/db.config.ini';
        DeefyRepository::setConfig($path);


        $repo = DeefyRepository::getInstance();

        $res = "";

        $tab = $repo->get_all_playlist();

        foreach ($tab as $key => $value) {
            $render = new AudioListRenderer($value);
            $res .= <<<HTML
                <a href="?action=add-track-podcast">{$render->render(1)} </a>
            HTML;
        }



        return $res;
    }



}



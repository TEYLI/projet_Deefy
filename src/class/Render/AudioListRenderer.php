<?php
namespace Iutnc\Deefy\Render;

use Iutnc\Deefy\Audio\Track\AlbumTrack;

class AudioListRenderer implements Renderer
{
    private \Iutnc\Deefy\Audio\Lists\AudioList $audioList;

    public function __construct(\Iutnc\Deefy\Audio\Lists\AudioList $audioList)
    {
        $this->audioList = $audioList;
    }

    public function render(int $selector): string
    {
        $nom = htmlspecialchars($this->audioList->nom);
        $res = "<h3>$nom</h3>";

        foreach ($this->audioList->liste_pistes as $value) {
            if ($value instanceof AlbumTrack) {
                $track = new AlbumTrackRender($value);
            } else {
                $track = new PodcastRender($value);
            }
            $res .= $track->render(Renderer::COMPACT);
        }

        $res .= "<p>Nombre de pistes : {$this->audioList->nb_pistes}</p>";
        $res .= "<p>Durée totale : {$this->audioList->duree_total} s</p>";

        return $res;
    }
}
<?php
namespace Iutnc\Deefy\render;

use Iutnc\Deefy\audio\track\AlbumTrack;

class AudioListRenderer implements Renderer
{
    private \Iutnc\Deefy\audio\lists\AudioList $audioList;

    public function __construct(\Iutnc\Deefy\audio\lists\AudioList $audioList)
    {
        $this->audioList = $audioList;
    }

    public function render(int $selector): string
    {
        $res = "";
        switch ($selector) {
            case Renderer::COMPACT:
                $nom = htmlspecialchars($this->audioList->nom);
                $res = "<h2 class=\"h5 card-title mb-0\">$nom</h2>";
                break;

            case Renderer::LONG:
                $nom = htmlspecialchars($this->audioList->nom);
                $res = "<h1 class=\"h3 mb-3\">$nom</h1>";
                $res .= "<div class=\"d-flex flex-wrap gap-2 mb-3\">";
                $res .= "<span class=\"badge text-bg-secondary\">Nombre de pistes : {$this->audioList->nb_pistes}</span>";
                $res .= "<span class=\"badge text-bg-secondary\">Durée totale : {$this->audioList->duree_total} s</span>";
                $res .= "</div>";
                $res .= "<div class=\"d-grid gap-3\">";

                foreach ($this->audioList->liste_pistes as $value) {
                    if ($value instanceof AlbumTrack) {
                        $track = new AlbumTrackRender($value);
                    } else {
                        $track = new PodcastRender($value);
                    }
                    $res .= $track->render($selector);
                }
                $res .= "</div>";
                break;

            default:
                $nom = htmlspecialchars($this->audioList->nom);
                $res = "<h2 class=\"h5 card-title mb-0\">$nom</h2>";
                break;
        }

        return $res;
    }
}
<?php

namespace Iutnc\Deefy\render;
class AlbumTrackRender extends AudioTrackRender
{

    private \Iutnc\Deefy\audio\track\AlbumTrack $albumTrack;

    public function __construct(\Iutnc\Deefy\audio\track\AlbumTrack $albumTrack_para)
    {
        $this->albumTrack = $albumTrack_para;

    }

    public function __get(string $name): mixed
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
        throw new \Iutnc\Deefy\exception\InvalidPropertyNameException("invalid property : $name");
    }

    public function __set(string $name, mixed $value): void
    {
        if (property_exists($this, $name)) {
            $this->$name = $value;
        } else {
            throw new \Iutnc\Deefy\exception\InvalidPropertyNameException("invalid property : $name");
        }
    }



    protected function render_html(int $selector): string
    {
        $res = "";
        $data = json_decode($this->albumTrack);
        $title = htmlspecialchars($data->title, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $album = htmlspecialchars($data->album, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $trackNumber = htmlspecialchars((string) $data->numero_pistes, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $audioFile = $this->audioSource($data->nom_fichier);
        switch ($selector) {
            case Renderer::COMPACT:
                $res =
                    '<div class="card card-body track-card">' .
                    "<p class=\"fw-semibold mb-2\">$title — $album</p>" .
                    "<audio class=\"w-100\" controls>" .
                    "<source src=\"$audioFile\" type=\"audio/mpeg\">" .
                    "</audio></div>";
                break;

            case Renderer::LONG:
                $res =
                    '<div class="card card-body track-card">' .
                    "<p class=\"fw-semibold mb-2\">Piste $trackNumber — $title — $album</p>" .
                    "<audio class=\"w-100\" controls>" .
                    "<source src=\"$audioFile\" type=\"audio/mpeg\">" .
                    "</audio></div>";
                break;

            default:
                $res =
                    "<audio controls>
                <source src=\"$audioFile\" type=\"audio/mpeg\">
                </audio>
                
                <p> $title-$album </p>
                ";
                break;
        }

        return $res;
    }

}

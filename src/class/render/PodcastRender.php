<?php
namespace Iutnc\Deefy\render;
final class PodcastRender  extends AudioTrackRender 
{
    private \Iutnc\Deefy\audio\track\PodcastTrack $podcasts;

    public function __construct(\Iutnc\Deefy\audio\track\PodcastTrack $PodcastTrack_para)
    {
        $this->podcasts = $PodcastTrack_para;

    }

    public function __get(string $name) : mixed{
        if(property_exists($this, $name)){return $this->$name;}
        throw new \Iutnc\Deefy\exception\InvalidPropertyNameException("invalid property : $name");
    }

    public function __set(string $name, mixed $value): void{
        if(property_exists($this, $name)){ $this->$name = $value;} else{
        throw new \Iutnc\Deefy\exception\InvalidPropertyNameException("invalid property : $name");}
    }
    

    protected function render_html(int $selector): string
    {
        $res = "";
        $data = json_decode($this->podcasts);
        $title = htmlspecialchars($data->title, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $date = htmlspecialchars($data->date, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $audioFile = $this->audioSource($data->nom_fichier);
        switch ($selector) {
            case Renderer::COMPACT:
                $res =
                    '<div class="card card-body track-card">' .
                    "<p class=\"fw-semibold mb-2\">$title</p>" .
                    "<audio class=\"w-100\" controls>" .
                    "<source src=\"$audioFile\" type=\"audio/mpeg\">" .
                    "</audio></div>";
                break;

            case Renderer::LONG:
                $res =
                    '<div class="card card-body track-card">' .
                    "<p class=\"fw-semibold mb-2\">$title</p>" .
                    "<p class=\"text-secondary small mb-2\">Publié le $date</p>" .
                    "<audio class=\"w-100\" controls>" .
                    "<source src=\"$audioFile\" type=\"audio/mpeg\">" .
                    "</audio></div>";
                break;

            default:
                $res =
                    "<audio controls>
                <source src=\"$audioFile\" type=\"audio/mpeg\">
                </audio>
                
                <p> $title </p>
                ";
                break;
        }

        return $res;
    }
}

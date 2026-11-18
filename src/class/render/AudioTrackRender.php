<?php

namespace Iutnc\Deefy\render;

abstract class AudioTrackRender implements Renderer {

    protected function audioSource(string $filename): string
{
    $cleanPath = ltrim($filename, '/');
    if (!str_starts_with($cleanPath, 'audio/')) {
        $cleanPath = 'audio/' . $cleanPath;
    }
    return htmlspecialchars($cleanPath, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

    public function render(int $selector): string
    {
        $res = "";
        switch ($selector) {
            case Renderer::COMPACT:
                $res = $this->render_html(Renderer::COMPACT);
                break;

            case Renderer::LONG:
                $res =$this->render_html(Renderer::LONG);
                break;

            default:
                $res = $this->render_html(Renderer::COMPACT);
                break;
        }

        return $res;
    }

   

    abstract protected function render_html(int $selector): string;

}
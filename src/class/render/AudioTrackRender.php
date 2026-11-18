<?php

namespace Iutnc\Deefy\render;

abstract class AudioTrackRender implements Renderer {

    protected function audioSource(string $path): string
    {
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
        $basePath = rtrim(dirname($scriptName), '/\\');
        $url = $basePath . '/class/audio/' . rawurlencode(basename($path));

        return htmlspecialchars($url, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
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
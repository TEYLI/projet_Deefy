<?php
namespace iutnc\deefy\dispatch;
class Dispatcher
{
    protected ?string $action = null;
    protected $action_tab = [];
    public function __construct()
    {
        $this->action = $_GET['action'] ?? 'default';
    }
    public function addRoute(string $action, string $action_class): Dispatcher
    {
        $this->action_tab[$action] = $action_class;
        return $this;
    }
    public function run(): void
    {
        if (isset($this->action_tab[$this->action])) {
            $actionInstance = new $this->action_tab[$this->action]();
            $this->renderPage($actionInstance->execute());
        } else {
            throw new \Exception("Action '{$this->action}' not found");
        }
    }
    private function renderPage(string $html): void
    {
        echo <<<HTML
            <!DOCTYPE html>
            <html lang="fr">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Deefy</title>
            </head>
            <body>

                <a href="?action=default">Accueil</a> |
                <a href="?action=playlists">Mes playlists</a> |
                <a href="?action=current-playlist">Playlist courante</a> |
                <a href="?action=add-playlist">Créer playlist</a>


                $html
            </body>
        </html>
    HTML;
    }
}
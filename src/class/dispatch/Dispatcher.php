<?php
namespace Iutnc\Deefy\dispatch;
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
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    :root {
                        color-scheme: dark;
                        --deefy-bg: #111116;
                        --deefy-surface: #1c1c24;
                        --deefy-surface-raised: #252530;
                        --deefy-text: #f5f5f7;
                        --deefy-muted: #a7a7b3;
                        --deefy-green: #1ed760;
                        --deefy-pink: #e83e8c;
                        --deefy-purple: #8b5cf6;
                    }
                    body {
                        min-height: 100vh;
                        color: var(--deefy-text);
                        background:
                            radial-gradient(ellipse at 12% 0%, rgb(232 62 140 / 13%), transparent 32rem),
                            radial-gradient(ellipse at 90% 5%, rgb(30 215 96 / 9%), transparent 28rem),
                            var(--deefy-bg);
                    }
                    .deefy-navbar {
                        min-height: 76px;
                        background: rgb(16 16 20 / 92%);
                        border-bottom: 1px solid rgb(255 255 255 / 8%);
                        backdrop-filter: blur(16px);
                    }
                    .deefy-navbar .navbar-brand {
                        font-size: 1.6rem;
                        letter-spacing: -1px;
                        background: linear-gradient(100deg, var(--deefy-green), #fff 45%, var(--deefy-pink));
                        background-clip: text;
                        -webkit-text-fill-color: transparent;
                    }
                    .deefy-navbar .nav-link {
                        color: #dcdce4;
                        font-weight: 600;
                        padding: 0.55rem 1rem;
                        border-radius: 999px;
                        transition: 0.2s ease;
                    }
                    .deefy-navbar .nav-link:hover,
                    .deefy-navbar .nav-link:focus {
                        color: var(--deefy-text);
                        background: rgb(255 255 255 / 10%);
                    }
                    main.container {
                        max-width: 1120px;
                    }
                    .card {
                        color: var(--deefy-text);
                        background: var(--deefy-surface);
                        border: 1px solid rgb(255 255 255 / 8%) !important;
                        border-radius: 1rem;
                    }
                    .card-body {
                        border-radius: inherit;
                    }
                    .card.shadow-sm {
                        transition: transform 0.2s ease, border-color 0.2s ease;
                    }
                    a.text-decoration-none .card:hover {
                        transform: translateY(-3px);
                        border-color: rgb(30 215 96 / 55%) !important;
                    }
                    .hero-card {
                        overflow: hidden;
                        background:
                            linear-gradient(135deg, rgb(30 215 96 / 8%), transparent 45%),
                            linear-gradient(315deg, rgb(232 62 140 / 13%), transparent 55%),
                            var(--deefy-surface);
                    }
                    .track-card {
                        background: var(--deefy-surface-raised);
                    }
                    .card-title,
                    h1,
                    h2,
                    h3,
                    .form-label {
                        color: var(--deefy-text);
                    }
                    .text-secondary {
                        color: var(--deefy-muted) !important;
                    }
                    .text-dark {
                        color: var(--deefy-text) !important;
                    }
                    .btn-primary {
                        --bs-btn-bg: var(--deefy-green);
                        --bs-btn-border-color: var(--deefy-green);
                        --bs-btn-color: #111;
                        --bs-btn-hover-bg: #18bd52;
                        --bs-btn-hover-border-color: #18bd52;
                        --bs-btn-hover-color: #111;
                        --bs-btn-active-bg: #18bd52;
                        --bs-btn-active-border-color: #18bd52;
                        font-weight: 700;
                    }
                    .btn-outline-primary {
                        --bs-btn-color: var(--deefy-green);
                        --bs-btn-border-color: var(--deefy-green);
                        --bs-btn-hover-bg: var(--deefy-green);
                        --bs-btn-hover-border-color: var(--deefy-green);
                        --bs-btn-hover-color: #111;
                    }
                    .btn-outline-secondary {
                        --bs-btn-color: #e1e1e7;
                        --bs-btn-border-color: #666675;
                        --bs-btn-hover-bg: var(--deefy-surface-raised);
                        --bs-btn-hover-border-color: #888899;
                    }
                    .form-control {
                        color: var(--deefy-text);
                        background-color: #14141a;
                        border-color: #41414d;
                    }
                    .form-control:focus {
                        color: var(--deefy-text);
                        background-color: #14141a;
                        border-color: var(--deefy-green);
                        box-shadow: 0 0 0 0.2rem rgb(30 215 96 / 18%);
                    }
                    .form-control::file-selector-button {
                        color: var(--deefy-text);
                        background-color: var(--deefy-surface-raised);
                    }
                    .badge.text-bg-secondary {
                        color: #f5f5f7 !important;
                        background: linear-gradient(100deg, #55318c, #9e316f) !important;
                    }
                    .alert {
                        color: var(--deefy-text);
                        background-color: var(--deefy-surface);
                        border-color: rgb(255 255 255 / 12%);
                    }
                    .alert a:not(.btn) {
                        color: var(--deefy-green);
                    }
                    .deefy-navbar .navbar-toggler {
                        border: 0;
                    }
                    @media (min-width: 992px) {
                        .deefy-navbar .deefy-nav-links {
                            position: absolute;
                            left: 50%;
                            top: 50%;
                            transform: translate(-50%, -50%);
                            flex-direction: row;
                            align-items: center;
                        }
                    }
                </style>
            </head>
            <body>
                <nav class="navbar navbar-expand-lg navbar-dark deefy-navbar mb-4">
                    <div class="container position-relative">
                        <a class="navbar-brand fw-bold" href="?action=default">Deefy</a>
                        <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="mainNav">
                            <div class="navbar-nav deefy-nav-links gap-1">
                                <a class="nav-link" href="?action=default">Accueil</a>
                                <a class="nav-link" href="?action=playlists">Mes playlists</a>
                                <a class="nav-link" href="?action=current-playlist">Playlist courante</a>
                                <a class="nav-link" href="?action=add-playlist">Créer playlist</a>
                            </div>
                        </div>
                    </div>
                </nav>

                <main class="container pb-5">
                    $html
                </main>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
            </body>
        </html>
    HTML;
    }
}
<?php

namespace Iutnc\Deefy\Action;

class DefaultAction extends Action
{
    public function __construct()
    {
        parent::__construct();
    }


   
    public function execute(): string
    {
        return <<<HTML
            <div class="card hero-card border-0 shadow-sm">
                <div class="card-body p-5 text-center">
                    <span class="badge rounded-pill text-bg-secondary mb-3">Votre musique, votre univers</span>
                    <h1 class="display-5 fw-bold mb-3">Bienvenue sur DeefyApp !</h1>
                    <p class="lead text-secondary mb-4">Retrouvez vos playlists et vos morceaux au même endroit.</p>
                    <div class="d-flex flex-wrap justify-content-center gap-2">
                        <a class="btn btn-primary" href="?action=playlists">Voir mes playlists</a>
                        <a class="btn btn-outline-primary" href="?action=add-playlist">Créer une playlist</a>
                    </div>
                </div>
            </div>
        HTML;
    }

}

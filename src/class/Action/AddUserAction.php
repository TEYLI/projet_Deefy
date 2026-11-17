<?php

namespace Iutnc\Deefy\Action;

class AddUserAction extends Action
{
    public function __construct()
    {
        parent::__construct();
    }


    public function execute(): string
    {

        
        $res = "";

        if ($this->http_method === "GET") {
            $res = <<<HTML
                <div class="row justify-content-center">
                    <div class="col-12 col-md-8 col-lg-6">
                        <div class="card shadow-sm">
                            <div class="card-body p-4">
                                <h1 class="h3 mb-4">Connexion</h1>
                                <form method="post" action="?action=add-user">
                                    <div class="mb-3">
                                        <label class="form-label" for="user-name">Nom</label>
                                        <input class="form-control" id="user-name" type="text" name="nom" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="user-email">Email</label>
                                        <input class="form-control" id="user-email" type="email" name="mail" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="user-age">Age</label>
                                        <input class="form-control" id="user-age" type="number" name="age" required>
                                    </div>
                                    <button class="btn btn-primary" type="submit">Connexion</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
              HTML;
        } else {
            //values input
            $nom = filter_var($_POST["nom"], FILTER_SANITIZE_SPECIAL_CHARS);
            $mail = filter_var($_POST["mail"], FILTER_VALIDATE_EMAIL);
            $age = filter_var($_POST["age"], FILTER_VALIDATE_INT);


            if (!(isset($_SESSION["user"][$mail]))) {
                $_SESSION["user"][$mail][] = $nom;
                $_SESSION["user"][$mail][] = $age;
            }


            $res = <<<HTML

                <p> Bravo connexion réussi !!! </p>
                <p>Nom : $nom, Email: $mail, Age: $age</p>
                <a href="?action=default">retour page d'accueille</a>

            HTML;

        }

        return $res;

    }
}

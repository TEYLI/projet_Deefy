<?php

namespace Iutnc\Deefy\Repository;

use PDO;
use Iutnc\Deefy\Audio\Track\AudioTrack;
use Iutnc\Deefy\Audio\Track\AlbumTrack;
use Iutnc\Deefy\Audio\Track\PodcastTrack;
use Iutnc\Deefy\Audio\Lists\Playlists;

class DeefyRepository
{
    private \PDO $pdo;
    private static ?DeefyRepository $instance = null;
    private static array $config = [];

    private function __construct(array $conf)
    {
        $this->pdo = new \PDO(
            $conf['dsn'],
            $conf['user'],
            $conf['pass'],
            [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
        );
    }

    public static function getInstance(): DeefyRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new DeefyRepository(self::$config);
        }
        return self::$instance;
    }

    public static function setConfig(string $file): void
    {
        $conf = parse_ini_file($file);
        if ($conf === false) {
            throw new \Exception("Error reading configuration file");
        }
        self::$config = [
            'dsn' => $conf['driver'] . ":host=" . $conf['host'] . ";dbname=" . $conf['database'] . ";charset=utf8mb4",
            'user' => $conf['username'],
            'pass' => $conf['password']
        ];
    }

    // F4 : Sauvegarder une playlist vide et récupérer son ID
    public function saveEmptyPlaylist(Playlists $pk): Playlists
    {
        $query = "INSERT INTO playlist (nom) VALUES (:nom)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['nom' => $pk->nom]);
        $pk->setID((int)$this->pdo->lastInsertId());
        return $pk;
    }

    // F1 : Récupérer toutes les playlists avec leur ID
    public function get_all_playlist(): array
    {
        $tab_playlists = [];
        $query = "SELECT id, nom FROM playlist";
        $stmt = $this->pdo->query($query);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $pl = new Playlists($row['nom']);
            $pl->setID((int)$row['id']);
            $tab_playlists[] = $pl;
        }
        return $tab_playlists;
    }

    // F2 : Charger une playlist complète et toutes ses pistes par son ID
    public function findPlaylistById(int $id): ?Playlists
    {
        $stmt = $this->pdo->prepare("SELECT id, nom FROM playlist WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        $playlist = new Playlists($row['nom']);
        $playlist->setID((int)$row['id']);

        $queryTracks = <<<SQL
            SELECT t.* FROM track t
            INNER JOIN playlist2track p2t ON t.id = p2t.id_track
            WHERE p2t.id_pl = :id_pl
            ORDER BY p2t.no_piste_dans_liste ASC
        SQL;
        $stmtTracks = $this->pdo->prepare($queryTracks);
        $stmtTracks->execute(['id_pl' => $id]);

        while ($t = $stmtTracks->fetch(PDO::FETCH_ASSOC)) {
            if ($t['type'] === 'A') {
                $track = new AlbumTrack($t['titre'], $t['filename'], $t['titre_album'] ?? '', (int)($t['numero_album'] ?? 0));
                $track->artiste = $t['artiste_album'];
                $track->annee = (int)$t['annee_album'];
            } else {
                $track = new PodcastTrack($t['date_podcast'] ?? '', $t['titre'], $t['filename']);
                $track->artiste = $t['auteur_podcast'];
            }
            $track->genre = $t['genre'];
            $track->duree = (int)$t['duree'];
            $track->setID((int)$t['id']);
            $playlist->add_piste($track);
        }

        return $playlist;
    }

    // F3 : Sauvegarder une piste (AlbumTrack ou PodcastTrack)
    public function savePiste(AudioTrack $piste): AudioTrack
    {
        if ($piste instanceof AlbumTrack) {
            $query = "INSERT INTO track (titre, genre, duree, filename, type, artiste_album, titre_album, annee_album, numero_album) 
                      VALUES (:titre, :genre, :duree, :filename, :type, :artiste_album, :titre_album, :annee_album, :numero_album)";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                'titre' => $piste->title,
                'genre' => $piste->genre,
                'duree' => $piste->duree,
                'filename' => $piste->nom_fichier,
                'type' => 'A',
                'artiste_album' => $piste->artiste,
                'titre_album' => $piste->album,
                'annee_album' => $piste->annee,
                'numero_album' => $piste->numero_pistes
            ]);
            $piste->setID((int)$this->pdo->lastInsertId());
            return $piste;
        } else {
            $query = "INSERT INTO track (titre, genre, duree, filename, type, auteur_podcast, date_podcast) 
                      VALUES (:titre, :genre, :duree, :filename, :type, :auteur_podcast, :date_podcast)";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                'titre' => $piste->title,
                'genre' => $piste->genre,
                'duree' => $piste->duree,
                'filename' => $piste->nom_fichier,
                'type' => 'P',
                'auteur_podcast' => $piste->artiste,
                'date_podcast' => $piste->date
            ]);
            $piste->setID((int)$this->pdo->lastInsertId());
            return $piste;
        }
    }

    // F3 : Lier une piste enregistrée à une playlist dans playlist2track
    public function addTrackToPlaylist(int $idPlaylist, int $idTrack, int $noPiste): void
    {
        $query = "INSERT INTO playlist2track (id_pl, id_track, no_piste_dans_liste) 
                  VALUES (:id_pl, :id_track, :no_piste_dans_liste)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            'id_pl' => $idPlaylist,
            'id_track' => $idTrack,
            'no_piste_dans_liste' => $noPiste
        ]);
    }
}
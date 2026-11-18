# Tableau de bord — Projet Deefy

**Binôme :** Holvec Ange & Le Madec Jean 
**Dépôt Git :** `https://github.com/TEYLI/projet_Deefy`  
**URL de test (webetu) :** `https://webetu.iutnc.univ-lorraine.fr/e79363u/projet_Deefy/`  

---

## 1. État d'avancement des fonctionnalités

| Code | Fonctionnalité | Statut | Action / Route | Détails techniques & classes associées |
| :--- | :--- | :---: | :--- | :--- |
| **F0** | **Page d'accueil & Navigation** | ✅ Réalisé | `?action=default` | `DefaultAction`, `Dispatcher` (menu global et routage centralisé) |
| **F1** | **Afficher toutes les playlists** | ✅ Réalisé | `?action=playlists` | `DisplayAllPlaylistsAction` — Récupération des playlists depuis MySQL via `DeefyRepository::get_all_playlist()` |
| **F2** | **Afficher une playlist sélectionnée** | ✅ Réalisé | `?action=display-playlist&id=<id>` | `DisplayPlaylistAction` — Chargement complet de la playlist et de ses pistes triées (`findPlaylistById()`), mise en session (`$_SESSION['playlist']`) et rendu HTML (`AudioListRenderer`) |
| **F3** | **Ajouter une piste (Album / Podcast)** | ✅ Réalisé | `?action=add-track-album`<br>`?action=add-track-podcast` | `AddAlbumTrackAction`, `AddPodcastTrackAction` — Vérification du format `.mp3`, upload dans le dossier `audio/`, insertion dans les tables `track` et `playlist2track` |
| **F4** | **Créer une playlist** | ✅ Réalisé | `?action=add-playlist` | `AddPlaylistAction` — Création de l'objet `Playlists`, sauvegarde en base (`saveEmptyPlaylist()`) et définition comme playlist courante en session |
| **F5** | **Afficher la playlist courante** | ✅ Réalisé | `?action=current-playlist` | `DisplayCurrentPlaylistAction` — Lecture et affichage de la playlist active stockée dans `$_SESSION['playlist']` |
---

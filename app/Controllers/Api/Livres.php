<?php

namespace App\Controllers\Api;

use App\Models\LivreModel;
use CodeIgniter\RESTful\ResourceController;

/**
 * Étape B : la ressource « livres » avec ResourceController.
 * Route : $routes->resource('api/livres', ['except' => 'new,edit']);
 * Lancez « php spark routes » pour voir quelle méthode répond à quel verbe.
 *
 * Méthodes utiles du ResponseTrait :
 *   respond($data, $code)       respondCreated($data)     respondNoContent()
 *   respondDeleted($data)       failNotFound($message)    failValidationErrors($erreurs)
 *   fail($message, $code)
 * Le Model est disponible dans $this->model ; ses erreurs dans $this->model->errors().
 */
class Livres extends ResourceController
{
    protected $modelName = LivreModel::class;
    protected $format    = 'json';

    // GET /api/livres
    public function index()
    {
        // Renvoyer les livres (200) au format du contrat : {"donnees": [ ...livres... ]}
        $livres = $this->model->findAll();

        return $this->respond([
            'donnees' => $livres
        ]);
    }

    // GET /api/livres/{id}
    public function show($id = null)
    {
        $livre = $this->model->find($id);

        if (! $livre) {
            return $this->failNotFound("Livre {$id} introuvable");
        }

        return $this->respond($livre);
    }

    // POST /api/livres
    public function create()
    {
        // TODO 1 : lire le corps JSON.
        $donnees = $this->request->getJSON(true) ?? [];

        // TODO 2 : insérer ; si la validation échoue, répondre 400 avec les erreurs.
        if (! $this->model->insert($donnees)) {
            return $this->failValidationErrors($this->model->errors());
        }

        $id = $this->model->getInsertID();
        $livreCree = $this->model->find($id);

        // TODO 3 : répondre 201 avec la ressource créée ET un en-tête Location.
        // Remarque : respondCreated() n'ajoute pas l'en-tête Location automatiquement,
        // il faut le définir manuellement via $this->response->setHeader().
        $this->response->setHeader('Location', site_url("api/livres/{$id}"));

        return $this->respondCreated($livreCree);
    }

    // PUT et PATCH /api/livres/{id} arrivent ici tous les deux.
    public function update($id = null)
    {
        // TODO 1 : 404 si le livre n'existe pas.
        $livre = $this->model->find($id);

        if (! $livre) {
            return $this->failNotFound("Livre {$id} introuvable");
        }

        $donnees = $this->request->getJSON(true) ?? [];
        $methode = strtoupper($this->request->getMethod());

        // TODO 2 : traiter PUT (remplacement complet) vs PATCH (modification partielle).
        if ($methode === 'PUT') {
            // Assure que toutes les données requises sont envoyées.
            // Si des champs manquent, le modèle déclenchera une erreur de validation.
            if (! $this->model->update($id, $donnees)) {
                return $this->failValidationErrors($this->model->errors());
            }
        } else {
            // PATCH : modification partielle. On valide et met à jour uniquement les champs fournis.
            if (! $this->model->skipValidation(false)->update($id, $donnees)) {
                return $this->failValidationErrors($this->model->errors());
            }
        }

        // TODO 3 : 200 avec la ressource à jour.
        $livreMisAJour = $this->model->find($id);

        return $this->respond($livreMisAJour);
    }

    // DELETE /api/livres/{id}
    public function delete($id = null)
    {
        // TODO : 404 si le livre n'existe pas, sinon supprimer.
        $livre = $this->model->find($id);

        if (! $livre) {
            return $this->failNotFound("Livre {$id} introuvable");
        }

        $this->model->delete($id);

        // Justification du choix de statut 200 :
        // Le statut HTTP 200 (avec respondDeleted) permet de renvoyer un corps JSON confirmant
        // la suppression (ex: id ou message de confirmation). Le statut 204 (respondNoContent)
        // serait également valide selon les standards REST s'il n'y a aucun corps de réponse à retourner.
        return $this->respondDeleted([
            'id'      => $id,
            'message' => "Livre {$id} supprimé avec succès"
        ]);
    }
}
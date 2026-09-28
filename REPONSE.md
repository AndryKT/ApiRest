# Q1. Après un POST réussi, l'article apparaît-il dans la liste rechargée ? Pourquoi ?
```
Non car l'API appele n'est que fictif , c'est juste une sorte de simulation
```

# Q7. Votre page est servie par localhost:8080 et appelle un autre domaine. Pourquoi le navigateur l'autorise-t-il ici ? Cherchez l'en-tête qui l'explique dans l'onglet Réseau.
```

```

# Q8 respondCreated() renvoie-t-il 201 ? Ajoute-t-il l'en-tête Location ? Vérifiez avec curl -i.

```
respondCreated() n'ajoute pas l'en-tête Location automatiquement, il faut le définir manuellement via $this->response->setHeader()
```

# Q9. Pour DELETE, avez-vous choisi 200 ou 204 ? Justifiez en une phrase, en lien avec le corps de la réponse.
```
On a choisi 200 pour DELETE car:permet de renvoyer un corps JSON confirmant et le 204 non
```

# Q10. Un PUT qui omet le champ auteur doit-il réussir ? Et un PATCH qui omet ce même champ ? Montrez comment votre code distingue les deux.
```
Un PUT qui omet le champ auteurne ne doit pas réussir car c'est un update complet et un PATCH le peut car c'est un update partiel et update seulement les champs qui sont present.
```
```php
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
```
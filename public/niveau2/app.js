// Niveau 2 : consommer une API REST existante avec fetch.
const API = 'https://jsonplaceholder.typicode.com/posts';
// const API = 'http://localhost:8081/api/livres';

const liste = document.getElementById('liste');
const message = document.getElementById('message');
const form = document.getElementById('form-article');

function afficher(texte, classe) {
  message.textContent = texte;
  message.className = classe;
}

// GET : les 5 premiers articles
async function charger() {
  // TODO 1 : appeler `${API}?_limit=5` avec l'en-tête Accept: application/json.
  const reponse = await fetch(`${API}?_limit=5`, {
    headers: {
      'Accept': 'application/json'
    }
  });
  // TODO 2 : si reponse.ok est faux, afficher le code d'erreur et s'arrêter.
  if (!reponse.ok) {
    afficher(`Erreur ${reponse.status}`, 'error');
    return;
  }
  // TODO 3 : vider #liste, puis créer un <li> par article (id et title)
  //          avec un bouton « Supprimer » qui appelle supprimer(article.id).
  liste.innerHTML = '';

  const articles = await reponse.json();

  articles.forEach(article => {
    const li = document.createElement('li');
    li.textContent = `${article.id} ${article.title} `;

    const btnSupprimer = document.createElement('button');
    btnSupprimer.textContent = 'Supprimer';
    btnSupprimer.addEventListener('click', () => supprimer(article.id));

    li.appendChild(btnSupprimer);
    liste.appendChild(li);
  });

}

// POST : créer un article
form.addEventListener('submit', async (e) => {
  e.preventDefault();
  const { title, body } = Object.fromEntries(new FormData(form));
  // TODO 4 : envoyer { title, body, userId: 1 } en JSON (POST, en-tête Content-Type).
  const reponse = await fetch(API, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      title,
      body,
      userId: 1
    })
  });

  // TODO 5 : si le statut est 201, afficher l'identifiant attribué puis recharger la liste.
  //          Le nouvel article apparaît-il ? Pourquoi ?
  if (reponse.status === 201) {
    const nouvelArticle = await reponse.json();
    afficher(`Article créé avec succès (ID : ${nouvelArticle.id})`, 'succes');
    form.reset();

    // Rechargement de la liste
    await charger();
  } else {
    afficher(`Échec de la création : ${reponse.status}`, 'erreur');
  }
});

// DELETE : supprimer un article
async function supprimer(id) {
  try {
    // TODO 6 : envoyer DELETE sur `${API}/${id}` et afficher le code reçu.
    const reponse = await fetch(`${API}/${id}`, {
      method: 'DELETE'
    });

    afficher(`Suppression de l'article ${id} - Statut : ${reponse.status}`, 'succes');
  } catch (erreur) {
    afficher(`Erreur lors de la suppression : ${erreur.message}`, 'erreur');
  }
}

charger();

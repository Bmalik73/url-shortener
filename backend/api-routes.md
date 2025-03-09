# Documentation de l'API URL Shortener

Cette documentation décrit les endpoints disponibles dans l'API REST du service de raccourcissement d'URL.

## Endpoints

| Nom             | Méthode | Chemin           | Description                                     |
|-----------------|---------|------------------|-------------------------------------------------|
| api_create_url  | POST    | /api/urls        | Créer une URL raccourcie                        |
| api_get_url     | GET     | /api/urls/{code} | Obtenir les informations d'une URL raccourcie   |
| api_lookup_url  | POST    | /api/lookup      | Rechercher une URL par son code                 |
| redirect_url    | GET     | /{code}          | Rediriger vers l'URL originale                  |

## Détails des endpoints

### Créer une URL raccourcie (api_create_url)

**POST** `/api/urls`

Crée une URL raccourcie à partir d'une URL longue.

#### Paramètres (JSON)

| Nom              | Type    | Description                            | Requis |
|------------------|---------|----------------------------------------|--------|
| url              | string  | URL à raccourcir                       | Oui    |
| expiresInSeconds | integer | Durée de validité en secondes          | Non    |

#### Exemple de requête

curl -X POST http://localhost:8000/api/urls \
-H "Content-Type: application/json" \
-d '{"url":"https://www.example.com"}'

#### Exemple de réponse

json:backend/api-routes.md
{
"originalUrl": "https://www.example.com",
"shortUrl": "http://localhost:8000/shZEs1",
"code": "shZEs1",
"createdAt": "2025-03-09T20:55:38+00:00",
"expiresAt": "2026-03-09T20:55:38+00:00",
"visitCount": 0
}

### Obtenir les informations d'une URL raccourcie (api_get_url)

**GET** `/api/urls/{code}`

Récupère les informations d'une URL raccourcie à partir de son code.

#### Paramètres (URL)

| Nom  | Type   | Description                | Requis |
|------|--------|----------------------------|--------|
| code | string | Code de l'URL raccourcie   | Oui    |

#### Exemple de requête

curl -X GET http://localhost:8000/api/urls/shZEs1

#### Exemple de réponse

json:backend/api-routes.md
{
"originalUrl": "https://www.example.com",
"shortUrl": "http://localhost:8000/shZEs1",
"code": "shZEs1",
"createdAt": "2025-03-09T20:55:38+00:00",
"expiresAt": "2026-03-09T20:55:38+00:00",
"visitCount": 1
}

### Rechercher une URL par son code (api_lookup_url)

**POST** `/api/lookup`

Recherche une URL à partir de son code court.

#### Paramètres (JSON)

| Nom  | Type   | Description                | Requis |
|------|--------|----------------------------|--------|
| code | string | Code de l'URL raccourcie   | Oui    |

#### Exemple de requête

curl -X POST http://localhost:8000/api/lookup \
-H "Content-Type: application/json" \
-d '{"code":"shZEs1"}'

#### Exemple de réponse

json
{
"originalUrl": "https://www.example.com",
"shortUrl": "http://localhost:8000/shZEs1",
"code": "shZEs1",
"createdAt": "2025-03-09T20:55:38+00:00",
"expiresAt": "2026-03-09T20:55:38+00:00",
"visitCount": 2
}

### Rediriger vers l'URL originale (redirect_url)

**GET** `/{code}`

Redirige directement vers l'URL originale à partir du code court.

#### Paramètres (URL)

| Nom  | Type   | Description                | Requis |
|------|--------|----------------------------|--------|
| code | string | Code de l'URL raccourcie   | Oui    |

#### Comportement

Redirige (HTTP 302) vers l'URL originale correspondant au code.

## Codes d'erreur

| Code HTTP | Description                                           |
|-----------|-------------------------------------------------------|
| 400       | Requête invalide (paramètres manquants ou invalides)  |
| 404       | URL non trouvée                                       |
| 410       | URL expirée                                           |

## Notes

- Les URLs raccourcies peuvent avoir une date d'expiration. Après cette date, elles ne seront plus accessibles.
- Le compteur de visites est incrémenté à chaque redirection.
- Si aucune durée d'expiration n'est spécifiée, la valeur par défaut configurée sur le serveur sera utilisée.
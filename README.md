# PHPUnit — Exercice EmailValidator

Exercice issu du **jour 2** de la formation PHPUnit (introduction au TDD).

## Énoncé

### 1. Intro TDD — `EmailValidator`

Créer une classe `EmailValidator` qui permet de valider le format d'un email.

- La méthode à tester est `validate(string $email): bool`.
- Vérifier que la classe appelle bien la bonne URL : `https://email.verify/your@example.com`.
- Vérifier que la méthode renvoie `true` lorsque l'API retourne `{"format": true}`.

> Problème : cette API n'existe pas.
> → On s'appuie donc sur des tests unitaires avec un client HTTP mocké.

### 2. Cas d'erreurs

Tester que `validate()` renvoie `false` (ou lève l'exception adaptée) lorsque l'API renvoie un code HTTP `404`, `405` ou `500`.

### 3. L'API est sortie !

Écrire un **test d'intégration** en utilisant le vrai service [Disify](https://www.disify.com/) à la place de `https://email.verify`.

## Stack

- PHP 8.4
- PHPUnit 13.1
- Guzzle 7.10
- Docker / Docker Compose

## Lancer le projet

### Construire l'image et installer les dépendances

```bash
docker compose build
docker compose run --rm php composer install
```

### Ouvrir un shell dans le conteneur

```bash
make php/cli
```

### Lancer les tests

Depuis le conteneur :

```bash
vendor/bin/phpunit
```

Ou depuis l'hôte sans entrer dans le conteneur :

```bash
docker compose run --rm php vendor/bin/phpunit
```

### Lancer une suite spécifique

```bash
# Tests unitaires
docker compose run --rm php vendor/bin/phpunit tests/Unit

# Tests d'intégration
docker compose run --rm php vendor/bin/phpunit tests/Integration
```

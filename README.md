# 🛍️ Odento-SHOP

Bienvenue dans **Odento-SHOP**, un site e-commerce de démonstration développé avec **Symfony 6.4**, **EasyAdmin**, **Doctrine**, **Bootstrap** et **MySQL**.  
Le projet a été créé dans le cadre d’un apprentissage guidé avec accompagnement pédagogique.

---

## 🔧 Technologies principales

- Symfony 6.4 (LTS)
- PHP 8.2
- EasyAdmin
- Doctrine ORM
- Bootstrap 5
- MySQL (via XAMPP)
- Git / GitHub

---

## 📊 Fonctionnalités réalisées

✅ Gestion des produits (Articles)  
✅ Gestion des catégories (avec relation ManyToOne → Article)  
✅ Gestion des posts (blog)  
✅ **Upload d’images dans les posts via EasyAdmin**  
✅ Gestion des utilisateurs avec sécurité (User + authentification)  
✅ Dashboard complet avec EasyAdmin  
✅ Migrations Doctrine  
✅ Architecture MVC claire

---

## 🖼️ Upload d’image dans les Posts

- Champ `image` ajouté dans l’entité `Post`
- Dashboard EasyAdmin avec **champ image upload automatique**
- Stockage dans `public/images/`
- Nom du fichier géré automatiquement
- Image visible dans l’administration

---

## 🛠️ Installation du projet

### 1. Cloner le dépôt Git

```bash
git clone https://github.com/rigal34/odento.git
cd odento
```

### 2. Installer les dépendances PHP

```bash
composer install
```

### 3. Configuration de la base de données

Dans le fichier `.env` :

```env
DATABASE_URL="mysql://root:@127.0.0.1:3306/odento"
```

Créer la base et les tables :

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### 4. Lancer le serveur Symfony

```bash
symfony serve -d
```

Ou avec PHP directement :

```bash
php -S localhost:8000 -t public
```

### 5. Accéder à l’administration

- http://localhost:8000/admin  
- Connectez-vous avec un utilisateur existant ou créez-en un

---

## 📂 Structure du projet

```
odento/
├── assets/
├── bin/
├── config/
├── migrations/
├── public/
│   └── images/
├── src/
│   ├── Controller/
│   │   └── Admin/
│   ├── Entity/
│   ├── Form/
│   └── Security/
├── templates/
│   ├── base.html.twig
│   ├── dashboard.html.twig
│   └── post/
├── translations/
├── var/
├── .env
├── composer.json
└── README.md
```

---

## 💼 Notes personnelles

Projet conçu à des fins pédagogiques avec aide d’un professeur.  
Toutes les entités, relations, migrations, et la configuration EasyAdmin ont été construites progressivement.  

---

## 🔗 Liens utiles

- [Documentation Symfony](https://symfony.com/doc/current/index.html)
- [Documentation EasyAdmin](https://symfony.com/bundles/EasyAdminBundle/current/)
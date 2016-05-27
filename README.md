INFORMATION SUR LE BUNDLE:

Cet ensemble est conçu pour le framework Symfony 3 et PHP >= 5.x | 7.x

Annonce: Linkedin: https://www.linkedin.com/in/verdierdavid si mon travail vous satisfait sachez que je cherche à travailler avec le Framework Symfony

Disponible sur: 
* Github
* Packagist
* KnpBundles

CARACTERISTIQUES:

Ce bundle offre les fonctionnalités suivantes:

* Catégorie : Créer / modifier / supprimer / Déplacer un forum / Droits de lecture.
* Forum : Créer / Modifier / Supprimer / Déplacer un topic.
* Topics : Créer / Modifier / Supprimer / Déplacer
* Posts : Créer / Modifier / Supprimer
* Labels : Resolu / Épinglé / Fermé
* D'autres fonctionnalités arriveront par la suite


Installation du bundle forum de discutea

Avant de commencer installer KnpPaginatorBundle si cela n'est pas déjà fait.

1: Ajouter la dependance à votre configuration composer
    "discutea/forum-bundle": "dev-master"

2: Mettre à jour les paquets
   composer update

3: Enregistrer DForumBundle dans votre kernel
   # app/AppKernel.php
   new Discutea\DForumBundle\DForumBundle(),

4: Ajouter les routes
   # app/Config/routing.yml

   discutea_forum:
    resource: "@DForumBundle/Resources/config/routing.yml"
    prefix:   /

5: Ajouter la configuration du bundle:

# Configuration de l'entité utilisateur

    doctrine:
        orm:
            auto_generate_proxy_classes: "%kernel.debug%"
            naming_strategy: doctrine.orm.naming_strategy.underscore
            auto_mapping: true
            resolve_target_entities:
                Symfony\Component\Security\Core\User\UserInterface: IRCz\UsersBundle\Entity\Users
    
    # Configurer knp paginator attention changer bien le page_name
    knp_paginator:
        page_range: 3
        default_options:
            page_name: p
            sort_field_name: sort
            sort_direction_name: direction
            distinct: true
        template:
            pagination: KnpPaginatorBundle:Pagination:twitter_bootstrap_v3_pagination.html.twig
            sortable: KnpPaginatorBundle:Pagination:sortable_link.html.twig

    # Configuration discutea forum
    discutea_forum:
        preview:
            enabled: true
        knp_paginator:
            page_name: p
            topics:
                enabled: true
                per_page: 10
            posts:
                enabled: true
                per_page: 10
  
  6: Faites la mise à jour de la base de données
      php bin/console doctrine:schema:update --force
      
  C'est prêt, rendez-vous à l'adresse /forum et créez votre première categorie et votre premier forum
  
  Plus de documentation:
      - Installation
      - Modifier les vues
      - Les voters
      - Le twig helper
      - Ajouter des bbcodes
      - Ajouter un breadcrumb
  
  INFORMATIONS PRATIQUES
  
  Pour une aide ou demander des fonctionalités merci de me joindre sur IRC (Anglais ou Français)
    - serveur: irc.ircz.fr:6667
    - salon:   #IRCz
    
  Pour les problèmes, merci d'ouvrir un ticket sur GitHub

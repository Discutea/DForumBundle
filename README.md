ForumBundle README.
=============================


[Lisez-moi Français](https://github.com/Discutea/DForumBundle/blob/master/README_fr.md)



[![SensioLabsInsight](https://insight.sensiolabs.com/projects/3b4e49a6-9f64-4441-a88a-65c8f705b3d1/mini.png)](https://insight.sensiolabs.com/projects/3b4e49a6-9f64-4441-a88a-65c8f705b3d1) [![Build Status](https://api.travis-ci.org/Discutea/DForumBundle.png)](https://travis-ci.org/Discutea/DForumBundle) [![Latest Stable Version](https://poser.pugx.org/discutea/forum-bundle/v/stable.png)](https://packagist.org/packages/discutea/forum-bundle) [![Total Downloads](https://poser.pugx.org/discutea/forum-bundle/downloads)](https://packagist.org/packages/discutea/forum-bundle)
 
## BUNDLE INFORMATION:
 
This bundle has been conceived with and for Symfony 3 (PHP >= 5.x | 7.x)
 
[Annonce: If my work seems good to you, be aware that I am currently looking for a job.](https://www.linkedin.com/in/verdierdavid)
 
Available on:
* [Github](https://github.com/Discutea/DForumBundle)
* [Packagist](https://packagist.org/packages/discutea/forum-bundle)
* [KnpBundles](http://knpbundles.com/Discutea/DForumBundle)
 
FEATURES:
 
This bundle includes the following features:
 
* Category: Create / Edit / Delete / Move forums / User rights management.
* Forum: Create / Edit / Delete / Move topics
* Topics: Create / Edit / Delete / Move
* Posts: Create / Edit / Delete
* Labels: Resolved / Pinned / Closed
* Others, coming soon...
 
## SETUP:
 
Before setting up everything, this bundle requires that you install KnpPaginatorBundle.
 
1: Add the dependancy to your composer
 
 
    composer require discutea/forum-bundle
 
2: Register DForumBundle in the Symfony kernel
 
 
    <?php
    // app/AppKernel.php
    // ...
    public function registerBundles()
    {
        $bundles = [
            // ...
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new Discutea\DForumBundle\DForumBundle(),
            // ...
 
 
4: Add routes routes
 
 
    # app/Config/routing.yml
 
    discutea_forum:
        resource: "@DForumBundle/Resources/config/routing.yml"
        prefix:   /
 
5: Bundle configuration:
 
# User entity
 
 
    doctrine:
        orm:
            auto_generate_proxy_classes: "%kernel.debug%"
            naming_strategy: doctrine.orm.naming_strategy.underscore
            auto_mapping: true
            resolve_target_entities:
                Symfony\Component\Security\Core\User\UserInterface: IRCz\UsersBundle\Entity\Users
   
    # Configuration for knp paginator: don't forget to customize page_name
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
 
    # Configuration for discutea forum
    discutea_forum:
        preview:
            enabled: true
        knp_paginator:
            page_name: p  #see knp_paginator.default_option.page_name
            topics:
                enabled: true
                per_page: 10
            posts:
                enabled: true
                per_page: 10
 
6: Update the database
 
 
    php bin/console doctrine:schema:update --force
 
 
All set, browse /forum and start by creating your first category and forum
 
 
## MORE INFO
 
 
To get help regarding this bundle or to request features (English and French)
  - server: irc.ircz.fr:6667
  - channel:   #IRCz
   
To deal with potential issues, please open a ticket on GitHub.

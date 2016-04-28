# ForumBundle
Forum Bundle for symfony 3;x

xxx: require php intl
xxx: composer require twig/extensions

app/config/service.yml
    twig.extension.intl:
        class: Twig_Extensions_Extension_Intl
        tags:
            - { name: twig.extension }


Voter's list:

canReadCategory($category)
canReadForum($forum)
canReadTopic($topic)
canReplyTopic($topic)
canEditTopic($topic)
canReplyPost($post)
canEditPost($post)

#app/config.yml
framework:
    translator: ~

doctrine:
    orm:
        ...
        resolve_target_entities:
            Symfony\Component\Security\Core\User\UserInterface: Acme\YourUserBundle\Entity\User

knp_paginator:
    # default page range used in pagination control
    page_range: 3
    default_options:
        # page query parameter name
        page_name: p
        # sort field query parameter name
        sort_field_name: sort
        # sort direction query parameter name
        sort_direction_name: direction
        # ensure distinct results, useful when ORM queries are using GROUP BY statements
        distinct: true
    template:
        # sliding pagination controls template
        pagination: KnpPaginatorBundle:Pagination:twitter_bootstrap_v3_pagination.html.twig
        # sort link template
        sortable: KnpPaginatorBundle:Pagination:sortable_link.html.twig


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


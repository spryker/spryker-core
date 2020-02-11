<?php

return [
    [
        [
            'id_category_node' => 1,
            'fk_category' => 1,
            'fk_parent_category_node' => null,
            'is_main' => true,
            'is_root' => true,
            'node_order' => 0,
            'store' => 'DE',
            'spy_category' =>
                [
                    'id_category' => 1,
                    'fk_category_template' => 1,
                    'category_key' => 'demoshop',
                    'is_active' => true,
                    'is_clickable' => true,
                    'is_in_menu' => true,
                    'is_searchable' => false,
                    'spy_category_template' =>
                        [
                            'id_category_template' => 1,
                            'name' => 'Catalog (default)',
                            'template_path' => '@CatalogPage/views/catalog/catalog.twig',
                            'spy_categories' =>
                                [
                                    0 => '*RECURSION*',
                                ],
                        ],
                    'spy_category_attributes' =>
                        [
                            0 =>
                                [
                                    'id_category_attribute' => 1,
                                    'fk_category' => 1,
                                    'fk_locale' => 46,
                                    'category_image_name' => null,
                                    'meta_description' => 'Deutsche Version des Demoshop',
                                    'meta_keywords' => 'Deutsche Version des Demoshop',
                                    'meta_title' => 'Demoshop',
                                    'name' => 'Demoshop',
                                    'created_at' => '2019-11-01 11:18:19.720947',
                                    'updated_at' => '2019-11-01 11:18:19.720947',
                                    'spy_category' => '*RECURSION*',
                                ],
                        ],
                    'spy_category_nodes' =>
                        [
                            0 => '*RECURSION*',
                        ],
                ],
            'spy_urls' =>
                [
                    0 =>
                        [
                            'id_url' => 2,
                            'fk_locale' => 46,
                            'fk_resource_categorynode' => 1,
                            'fk_resource_page' => null,
                            'fk_resource_product_abstract' => null,
                            'fk_resource_product_set' => null,
                            'fk_resource_redirect' => null,
                            'url' => '/de',
                            'spy_category_node' => '*RECURSION*',
                        ],
                ],
        ],
        [
            'store' => 'DE',
            'locale' => 'de_DE',
            'type' => 'category',
            'is-active' => false,
            'search-result-data' =>
                [
                    'id_category' => 1,
                    'name' => 'Demoshop',
                    'url' => '/de',
                    'type' => 'category',
                ],
            'full-text-boosted' =>
                [
                    0 => 'Demoshop',
                ],
            'full-text' =>
                [
                    0 => 'Demoshop',
                    1 => 'Deutsche Version des Demoshop',
                    2 => 'Deutsche Version des Demoshop',
                ],
            'suggestion-terms' =>
                [
                    0 => 'Demoshop',
                ],
            'completion-terms' =>
                [
                    0 => 'Demoshop',
                ],
        ],
        'de_DE',
    ],
];

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\SearchRestApi\Fixtures;

class SearchSuggestionsResponseMock
{
    /**
     * @var array
     */
    protected $restSearchSuggestionsResponseMock;

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->restSearchSuggestionsResponseMock;
    }

    /**
     * @return $this
     */
    public function createRestSearchSuggestionsResponse(): self
    {
        $this->restSearchSuggestionsResponseMock = [];

        return $this;
    }

    /**
     * @return \SprykerTest\Glue\SearchRestApi\Fixtures\SearchSuggestionsResponseMock
     */
    public function addCompletionData(): self
    {
        $this->restSearchSuggestionsResponseMock['completion'] = [
            'hp pro tablet 608 g1',
            'lenovo yoga tablet 3',
            'tablets',
        ];

        return $this;
    }

    /**
     * @return \SprykerTest\Glue\SearchRestApi\Fixtures\SearchSuggestionsResponseMock
     */
    public function addProductsAbstractData(): self
    {
        $this->restSearchSuggestionsResponseMock['suggestionByType']['product_abstract'] = [
            [
                'id_product_abstract' => 172,
                'abstract_sku' => '172',
                'abstract_name' => 'Lenovo Yoga Tablet 3',
                'url' => '/en/lenovo-yoga-tablet-3-172',
                'type' => 'product_abstract',
                'price' => 1988,
                'prices' => [
                    'DEFAULT' => 1988,
                ],
                'images' => [
                    [
                        'id_product_image' => 167,
                        'external_url_small' => '//images.icecat.biz/img/gallery_mediums/29801891_9454.jpg',
                        'external_url_large' => '//images.icecat.biz/img/gallery/29801891_9454.jpg',
                        'created_at' => '2018-07-06 11:59:36.227476',
                        'updated_at' => '2018-07-06 11:59:36.227476',
                        'id_product_image_set_to_product_image' => 386,
                        'fk_product_image_set' => 386,
                        'fk_product_image' => 167,
                        'sort_order' => 0,
                    ],
                ],
                'id_product_labels' => [],
            ],
            [
                'id_product_abstract' => 162,
                'abstract_sku' => '162',
                'abstract_name' => 'Asus ZenPad Z370C-1A',
                'url' => '/en/asus-zenpad-z370c-1a-162',
                'type' => 'product_abstract',
                'price' => 42131,
                'prices' => [
                    'DEFAULT' => 42131,
                ],
                'images' => [
                    [
                        'id_product_image' => 157,
                        'external_url_small' => '//images.icecat.biz/img/gallery_mediums/29533299_0030.jpg',
                        'external_url_large' => '//images.icecat.biz/img/gallery/29533299_0030.jpg',
                        'created_at' => '2018-07-06 12:01:29.463033',
                        'updated_at' => '2018-07-06 12:01:29.463033',
                        'id_product_image_set_to_product_image' => 376,
                        'fk_product_image_set' => 376,
                        'fk_product_image' => 157,
                        'sort_order' => 0,
                    ],
                ],
                'id_product_labels' => [],
            ],
        ];

        return $this;
    }

    /**
     * @return \SprykerTest\Glue\SearchRestApi\Fixtures\SearchSuggestionsResponseMock
     */
    public function addCategoriesData(): self
    {
        $this->restSearchSuggestionsResponseMock['suggestionByType']['category'] = [
            [
                'id_category' => 8,
                'name' => 'Tablets',
                'url' => '/en/computer/tablets',
                'type' => 'category',
            ],
            [
                'id_category' => 2,
                'name' => 'Cameras & Camcorders',
                'url' => '/en/cameras-&-camcorders',
                'type' => 'category',
            ],
        ];

        return $this;
    }

    /**
     * @return \SprykerTest\Glue\SearchRestApi\Fixtures\SearchSuggestionsResponseMock
     */
    public function addCmsPagesData(): self
    {
        $this->restSearchSuggestionsResponseMock['suggestionByType']['cms_page'] = [
            [
                'id_cms_page' => 6,
                'name' => 'Demo Landing Page',
                'type' => 'cms_page',
                'url' => '/en/demo-landing-page',
            ],
            [
                'id_cms_page' => 1,
                'name' => 'Imprint',
                'type' => 'cms_page',
                'url' => '/en/imprint',
            ],
        ];

        return $this;
    }
}

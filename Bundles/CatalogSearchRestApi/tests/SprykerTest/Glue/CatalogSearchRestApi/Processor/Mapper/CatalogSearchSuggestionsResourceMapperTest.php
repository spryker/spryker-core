<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\CatalogSearchRestApi\Processor\Mapper;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RestCatalogSearchSuggestionsAttributesTransfer;
use Spryker\Glue\CatalogSearchRestApi\Processor\Mapper\CatalogSearchSuggestionsResourceMapper;
use Spryker\Glue\CatalogSearchRestApi\Processor\Mapper\CatalogSearchSuggestionsResourceMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilder;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Glue
 * @group CatalogSearchRestApi
 * @group Processor
 * @group Mapper
 * @group CatalogSearchSuggestionsResourceMapperTest
 * Add your own group annotations below this line
 */
class CatalogSearchSuggestionsResourceMapperTest extends Unit
{
    protected const REQUESTED_CURRENCY = 'CHF';

    /**
     * @var \SprykerTest\Glue\CatalogSearchRestApi\CatalogSearchRestApiProcessorTester
     */
    protected $tester;

    /**
     * @var array
     */
    protected $restSearchSuggestionsResponseMock;

    /**
     * @return void
     */
    public function testRestResponseAttributesIsInstanceOfRestSearchSuggestionsAttributesTransfer(): void
    {
        $mapper = $this->getMapper();
        $restCatalogSearchSuggestionsAttributes = $mapper->mapSuggestionsToRestAttributesTransfer([], static::REQUESTED_CURRENCY);

        $this->assertInstanceOf(RestCatalogSearchSuggestionsAttributesTransfer::class, $restCatalogSearchSuggestionsAttributes);
    }

    /**
     * @return void
     */
    public function testEmptySearchSuggestionsResponseWillMapEmptyAttributes(): void
    {
        $mapper = $this->getMapper();
        $restCatalogSearchSuggestionsAttributes = $mapper->mapSuggestionsToRestAttributesTransfer([], static::REQUESTED_CURRENCY);

        $attributes = $restCatalogSearchSuggestionsAttributes;

        $this->assertEquals(static::REQUESTED_CURRENCY, $restCatalogSearchSuggestionsAttributes->getCurrency());
        $this->assertEmpty($attributes->getCompletion());
        $this->assertEmpty($attributes->getProducts());
        $this->assertEmpty($attributes->getCategories());
        $this->assertEmpty($attributes->getCmsPages());
    }

    /**
     * @return void
     */
    public function testRestSearchSuggestionsResponseAttributesContainsCorrectData(): void
    {
        $mapper = $this->getMapper();
        $searchSuggestionsResponseDataMock = $this
            ->createRestSearchSuggestionsResponse()
            ->addProductsAbstractData()
            ->addCategoriesData()
            ->addCmsPagesData()
            ->getData();

        $restCatalogSearchSuggestionsAttributes = $mapper->mapSuggestionsToRestAttributesTransfer(
            $searchSuggestionsResponseDataMock,
            static::REQUESTED_CURRENCY
        );

        $this->assertEquals(static::REQUESTED_CURRENCY, $restCatalogSearchSuggestionsAttributes->getCurrency());
        foreach ($restCatalogSearchSuggestionsAttributes->getProducts() as $product) {
            $this->assertArrayHasKey('abstract_sku', $product);
            $this->assertArrayHasKey('abstract_name', $product);
            $this->assertArrayHasKey('price', $product);
            $this->assertArrayHasKey('images', $product);
            $this->assertArrayNotHasKey('id_product_abstract', $product);
        }

        foreach ($restCatalogSearchSuggestionsAttributes->getCategories() as $category) {
            $this->assertArrayHasKey('name', $category);
            $this->assertArrayNotHasKey('id_category', $category);
        }

        foreach ($restCatalogSearchSuggestionsAttributes->getCmsPages() as $cmsPage) {
            $this->assertArrayHasKey('name', $cmsPage);
            $this->assertArrayNotHasKey('id_cms_page', $cmsPage);
        }
    }

    /**
     * @return \Spryker\Glue\CatalogSearchRestApi\Processor\Mapper\CatalogSearchSuggestionsResourceMapperInterface
     */
    protected function getMapper(): CatalogSearchSuggestionsResourceMapperInterface
    {
        return new CatalogSearchSuggestionsResourceMapper();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected function getResourceBuilder(): RestResourceBuilderInterface
    {
        return $this->getMockBuilder(RestResourceBuilder::class)
            ->enableProxyingToOriginalMethods()
            ->getMock();
    }

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
     * @return \SprykerTest\Glue\CatalogSearchRestApi\Processor\Mapper\CatalogSearchSuggestionsResourceMapperTest
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
     * @return \SprykerTest\Glue\CatalogSearchRestApi\Processor\Mapper\CatalogSearchSuggestionsResourceMapperTest
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
        ];

        return $this;
    }

    /**
     * @return \SprykerTest\Glue\CatalogSearchRestApi\Processor\Mapper\CatalogSearchSuggestionsResourceMapperTest
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
        ];

        return $this;
    }

    /**
     * @return \SprykerTest\Glue\CatalogSearchRestApi\Processor\Mapper\CatalogSearchSuggestionsResourceMapperTest
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
        ];

        return $this;
    }
}

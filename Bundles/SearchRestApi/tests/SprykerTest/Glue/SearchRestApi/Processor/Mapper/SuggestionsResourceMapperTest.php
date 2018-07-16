<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\Processor\Mapper;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RestSearchSuggestionsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilder;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\SearchRestApi\Processor\Mapper\SuggestionsResourceMapper;
use Spryker\Glue\SearchRestApi\Processor\Mapper\SuggestionsResourceMapperInterface;
use SprykerTest\Glue\SearchRestApi\Fixtures\SearchSuggestionsResponseMock;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Glue
 * @group Processor
 * @group Mapper
 * @group SuggestionsResourceMapperTest
 * Add your own group annotations below this line
 */
class SuggestionsResourceMapperTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\SearchRestApi\SearchRestApiProcessorTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testRestResponseAttributesIsInstanceOfRestSearchSuggestionsAttributesTransfer(): void
    {
        $mapper = $this->getMapper();
        $restResponse = $mapper->mapSuggestionsResponseAttributesTransferToRestResponse([]);

        $this->assertInstanceOf(RestSearchSuggestionsAttributesTransfer::class, $restResponse->getAttributes());
    }

    /**
     * @return void
     */
    public function testEmptySearchSuggestionsResponseWillMapIntoRestResponseWithEmptyAttributes(): void
    {
        $mapper = $this->getMapper();
        $restResponse = $mapper->mapSuggestionsResponseAttributesTransferToRestResponse([]);

        $attributes = $restResponse->getAttributes();

        $this->assertEmpty($attributes->getCompletion());
        $this->assertEmpty($attributes->getProducts());
        $this->assertEmpty($attributes->getCategories());
        $this->assertEmpty($attributes->getCmsPages());
    }

    /**
     * @return void
     */
    public function testRestSearchSuggestionsResponseIdIsNull(): void
    {
        $restResponse = $this->getMapper()->mapSuggestionsResponseAttributesTransferToRestResponse([]);

        $this->assertNull($restResponse->getId());
    }

    /**
     * @return void
     */
    public function testRestSearchSuggestionsResponseAttributesContainsCorrectData(): void
    {
        $mapper = $this->getMapper();
        $searchSuggestionsResponseDataMock = (new SearchSuggestionsResponseMock())
            ->createRestSearchSuggestionsResponse()
            ->addProductsAbstractData()
            ->addCategoriesData()
            ->addCmsPagesData()
            ->getData();

        $restResponse = $mapper->mapSuggestionsResponseAttributesTransferToRestResponse($searchSuggestionsResponseDataMock);

        foreach ($restResponse->getAttributes()->getProducts() as $product) {
            $this->assertArrayHasKey('abstract_sku', $product);
            $this->assertArrayHasKey('abstract_name', $product);
            $this->assertArrayHasKey('price', $product);
            $this->assertArrayHasKey('images', $product);
            $this->assertArrayNotHasKey('id_product_abstract', $product);
        }

        foreach ($restResponse->getAttributes()->getCategories() as $category) {
            $this->assertArrayHasKey('name', $category);
            $this->assertArrayNotHasKey('id_category', $category);
        }

        foreach ($restResponse->getAttributes()->getCmsPages() as $cmsPage) {
            $this->assertArrayHasKey('name', $cmsPage);
            $this->assertArrayNotHasKey('id_cms_page', $cmsPage);
        }
    }

    /**
     * @return \Spryker\Glue\SearchRestApi\Processor\Mapper\SuggestionsResourceMapperInterface
     */
    protected function getMapper(): SuggestionsResourceMapperInterface
    {
        return new SuggestionsResourceMapper($this->getResourceBuilder());
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
}

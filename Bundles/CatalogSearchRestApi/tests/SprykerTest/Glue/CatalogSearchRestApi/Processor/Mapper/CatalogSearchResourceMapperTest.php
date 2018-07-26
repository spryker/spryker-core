<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\CatalogSearchRestApi\Processor\Mapper;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PaginationSearchResultTransfer;
use Generated\Shared\Transfer\RestCatalogSearchAttributesTransfer;
use Generated\Shared\Transfer\SortSearchResultTransfer;
use Spryker\Glue\CatalogSearchRestApi\Processor\Mapper\CatalogSearchResourceMapper;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilder;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Glue
 * @group CatalogSearchRestApi
 * @group Processor
 * @group Mapper
 * @group CatalogSearchResourceMapperTest
 * Add your own group annotations below this line
 */
class CatalogSearchResourceMapperTest extends Unit
{
    protected const REQUESTED_CURRENCY = 'CHF';

    /**
     * @var \Spryker\Glue\CatalogSearchRestApi\Processor\Mapper\CatalogSearchResourceMapper
     */
    protected $catalogSearchResourceMapper;

    /**
     * @var \Generated\Shared\Transfer\RestCatalogSearchAttributesTransfer
     */
    protected $restSearchAttributesTransfer;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->restSearchAttributesTransfer = new RestCatalogSearchAttributesTransfer();
        $this->catalogSearchResourceMapper = new CatalogSearchResourceMapper(new RestResourceBuilder());
    }

    /**
     * @return void
     */
    public function testMapperWillReturnRestResponseWithNotEmptyAttributesData(): void
    {
        $this->restSearchAttributesTransfer = $this
            ->catalogSearchResourceMapper
            ->mapSearchResponseAttributesTransferToRestResponse(
                $this->mockRestSearchResponseTransfer(),
                static::REQUESTED_CURRENCY
            )
            ->getAttributes();

        $this->assertEquals(static::REQUESTED_CURRENCY, $this->restSearchAttributesTransfer->getCurrency());

        $this->assertEquals(1, $this->restSearchAttributesTransfer->getProducts()->count());
        $this->assertEquals("cameras", $this->restSearchAttributesTransfer->getSpellingSuggestion());

        $this->assertEquals("Toshiba CAMILEO S20", $this->restSearchAttributesTransfer->getProducts()[0]->getAbstractName());
        $this->assertEquals(19568, $this->restSearchAttributesTransfer->getProducts()[0]->getPrice());
        $this->assertEquals("209", $this->restSearchAttributesTransfer->getProducts()[0]->getAbstractSku());
        $this->assertEquals(19568, $this->restSearchAttributesTransfer->getProducts()[0]->getPrices()['DEFAULT']);
        $this->assertArrayNotHasKey("id_product_abstract", $this->restSearchAttributesTransfer->getProducts()[0]);
        $this->assertArrayNotHasKey("id_product_labels", $this->restSearchAttributesTransfer->getProducts()[0]);

        $this->assertArrayNotHasKey("fk_product_image_set", $this->restSearchAttributesTransfer->getProducts()[0]->getImages()[0]);
        $this->assertArrayNotHasKey("id_product_image", $this->restSearchAttributesTransfer->getProducts()[0]->getImages()[0]);
        $this->assertArrayNotHasKey("id_product_image_set_to_product_image", $this->restSearchAttributesTransfer->getProducts()[0]->getImages()[0]);
        $this->assertArrayNotHasKey("fk_product_image", $this->restSearchAttributesTransfer->getProducts()[0]->getImages()[0]);

        $this->assertEquals("//images.icecat.biz/img/norm/medium/15743_12554247-9579.jpg", $this->restSearchAttributesTransfer->getProducts()[0]->getImages()[0]['externalUrlSmall']);
        $this->assertEquals("//images.icecat.biz/img/norm/high/15743_12554247-9579.jpg", $this->restSearchAttributesTransfer->getProducts()[0]->getImages()[0]['externalUrlLarge']);

        $this->assertEquals("name_asc", $this->restSearchAttributesTransfer->getSort()->getCurrentSortOrder());
        $this->assertEquals("1", $this->restSearchAttributesTransfer->getSort()->getCurrentSortParam());
        $this->assertArraySubset($this->restSearchAttributesTransfer->getSort()->getSortParamNames(), ["rating", "name_asc", "name_desc", "price_asc", "price_desc"]);
        $this->assertArraySubset(["rating", "name_asc", "name_desc", "price_asc", "price_desc"], $this->restSearchAttributesTransfer->getSort()->getSortParamNames());

        $this->assertEquals(1, $this->restSearchAttributesTransfer->getPagination()->getCurrentPage());
        $this->assertEquals(12, $this->restSearchAttributesTransfer->getPagination()->getCurrentItemsPerPage());
        $this->assertEquals(1, $this->restSearchAttributesTransfer->getPagination()->getMaxPage());
        $this->assertEquals(3, $this->restSearchAttributesTransfer->getPagination()->getNumFound());
    }

    /**
     * @return void
     */
    public function testMapperWillReturnRestResponseWithEmptyAttributesData(): void
    {
        $this->restSearchAttributesTransfer = $this
            ->catalogSearchResourceMapper
            ->mapSearchResponseAttributesTransferToRestResponse(
                $this->mockEmptyRestSearchResponseTransfer(),
                static::REQUESTED_CURRENCY
            )
            ->getAttributes();

        $this->assertEquals(static::REQUESTED_CURRENCY, $this->restSearchAttributesTransfer->getCurrency());
        $this->assertEmpty($this->restSearchAttributesTransfer->getProducts());
    }

    /**
     * @return array
     */
    protected function mockRestSearchResponseTransfer(): array
    {
        $mockRestSearchResponse = [];
        $mockRestSearchResponse['products'] = $this->mockProducts();
        $mockRestSearchResponse['sort'] = $this->mockSort();
        $mockRestSearchResponse['pagination'] = $this->mockPagination();
        $mockRestSearchResponse['spellingSuggestion'] = 'cameras';

        return $mockRestSearchResponse;
    }

    /**
     * @return array
     */
    protected function mockEmptyRestSearchResponseTransfer(): array
    {
        $mockRestSearchResponse = [];
        $mockRestSearchResponse['products'] = [];
        $mockRestSearchResponse['sort'] = $this->mockSort();
        $mockRestSearchResponse['pagination'] = $this->mockPagination();
        $mockRestSearchResponse['spellingSuggestion'] = 'cameras';

        return $mockRestSearchResponse;
    }

    /**
     * @return array
     */
    protected function mockProducts(): array
    {
        $products = [];
        $products[] = [
            "images" => [
                [
                    "fk_product_image_set" => 423,
                    "id_product_image" => 204,
                    "external_url_small" => "//images.icecat.biz/img/norm/medium/15743_12554247-9579.jpg",
                    "external_url_large" => "//images.icecat.biz/img/norm/high/15743_12554247-9579.jpg",
                    "id_product_image_set_to_product_image" => 423,
                    "fk_product_image" => 204],
            ],
            "id_product_labels" => [
                0 => 2,
            ],
            "price" => 19568,
            "abstract_name" => "Toshiba CAMILEO S20",
            "id_product_abstract" => 209,
            "type" => "product_abstract",
            "prices" => [
                "DEFAULT" => 19568,
            ],
            "abstract_sku" => "209",
            "url" => "/en/toshiba-camileo-s20-209",
        ];

        return $products;
    }

    /**
     * @return \Generated\Shared\Transfer\SortSearchResultTransfer
     */
    protected function mockSort(): SortSearchResultTransfer
    {
        $sort = new SortSearchResultTransfer();
        $sort->setSortParamNames([
            "rating",
            "name_asc",
            "name_desc",
            "price_asc",
            "price_desc",
        ]);
        $sort->setCurrentSortOrder("name_asc");
        $sort->setCurrentSortParam("1");

        return $sort;
    }

    /**
     * @return \Generated\Shared\Transfer\PaginationSearchResultTransfer
     */
    protected function mockPagination(): PaginationSearchResultTransfer
    {
        $pagination = new PaginationSearchResultTransfer();
        $pagination->setNumFound(3);
        $pagination->setCurrentItemsPerPage(12);
        $pagination->setCurrentPage(1);
        $pagination->setMaxPage(1);

        return $pagination;
    }
}

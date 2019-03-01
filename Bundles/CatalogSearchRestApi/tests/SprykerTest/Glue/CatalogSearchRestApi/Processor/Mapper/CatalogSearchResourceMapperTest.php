<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\CatalogSearchRestApi\Processor\Mapper;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\FacetConfigTransfer;
use Generated\Shared\Transfer\FacetSearchResultTransfer;
use Generated\Shared\Transfer\FacetSearchResultValueTransfer;
use Generated\Shared\Transfer\PaginationSearchResultTransfer;
use Generated\Shared\Transfer\PriceModeConfigurationTransfer;
use Generated\Shared\Transfer\RangeSearchResultTransfer;
use Generated\Shared\Transfer\RestCatalogSearchAttributesTransfer;
use Generated\Shared\Transfer\SortSearchResultTransfer;
use Spryker\Client\Currency\CurrencyClient;
use Spryker\Glue\CatalogSearchRestApi\Dependency\Client\CatalogSearchRestApiToCurrencyClientBridge;
use Spryker\Glue\CatalogSearchRestApi\Processor\Mapper\CatalogSearchResourceMapper;

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
    protected const GROSS_AMOUNT = 'grossAmount';
    protected const GROSS_MODE = 'GROSS_MODE';
    protected const NET_MODE = 'NET_MODE';

    /** @deprecated Will be removed in next major release. */
    protected const KEY_PRODUCTS = 'products';
    protected const KEY_ABSTRACT_PRODUCTS = 'abstractProducts';

    /**
     * @var \Spryker\Glue\CatalogSearchRestApi\Processor\Mapper\CatalogSearchResourceMapper
     */
    protected $catalogSearchResourceMapper;

    /**
     * @var \Generated\Shared\Transfer\RestCatalogSearchAttributesTransfer
     */
    protected $restSearchAttributesTransfer;

    /**
     * @var \Spryker\Client\Currency\CurrencyClient|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $currencyClientMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->mockCurrencyClient();

        $this->restSearchAttributesTransfer = new RestCatalogSearchAttributesTransfer();
        $this->catalogSearchResourceMapper = new CatalogSearchResourceMapper(
            new CatalogSearchRestApiToCurrencyClientBridge($this->currencyClientMock)
        );
    }

    /**
     * @return void
     */
    public function testMapperWillReturnNotEmptyAttributesData(): void
    {
        $this->restSearchAttributesTransfer = $this
            ->catalogSearchResourceMapper
            ->mapSearchResultToRestAttributesTransfer(
                $this->mockRestSearchResponseTransfer()
            );

        $this->restSearchAttributesTransfer = $this->restSearchAttributesTransfer = $this
            ->catalogSearchResourceMapper
            ->mapPrices($this->restSearchAttributesTransfer, $this->getPriceModeInformation());

        $products = $this->getProductsFromRestCatalogSearchAttributesTransfer();
        $this->assertEquals(1, $products->count());
        $this->assertEquals("cameras", $this->restSearchAttributesTransfer->getSpellingSuggestion());

        $this->assertEquals("Toshiba CAMILEO S20", $products[0]->getAbstractName());
        $this->assertEquals(19568, $products[0]->getPrice());
        $this->assertEquals("209", $products[0]->getAbstractSku());
        $this->assertEquals(19568, $products[0]->getPrices()[0][static::GROSS_AMOUNT]);
        $this->assertArrayNotHasKey("id_product_abstract", $products[0]);
        $this->assertArrayNotHasKey("id_product_labels", $products[0]);

        $this->assertArrayNotHasKey("fk_product_image_set", $products[0]->getImages()[0]);
        $this->assertArrayNotHasKey("id_product_image", $products[0]->getImages()[0]);
        $this->assertArrayNotHasKey("id_product_image_set_to_product_image", $products[0]->getImages()[0]);
        $this->assertArrayNotHasKey("fk_product_image", $products[0]->getImages()[0]);

        $this->assertEquals("//images.icecat.biz/img/norm/medium/15743_12554247-9579.jpg", $products[0]->getImages()[0]['externalUrlSmall']);
        $this->assertEquals("//images.icecat.biz/img/norm/high/15743_12554247-9579.jpg", $products[0]->getImages()[0]['externalUrlLarge']);

        $this->assertEquals("name_asc", $this->restSearchAttributesTransfer->getSort()->getCurrentSortOrder());
        $this->assertEquals("1", $this->restSearchAttributesTransfer->getSort()->getCurrentSortParam());
        $this->assertArraySubset($this->restSearchAttributesTransfer->getSort()->getSortParamNames(), ["rating", "name_asc", "name_desc", "price_asc", "price_desc"]);
        $this->assertArraySubset(["rating", "name_asc", "name_desc", "price_asc", "price_desc"], $this->restSearchAttributesTransfer->getSort()->getSortParamNames());

        $this->assertEquals(1, $this->restSearchAttributesTransfer->getPagination()->getCurrentPage());
        $this->assertEquals(12, $this->restSearchAttributesTransfer->getPagination()->getCurrentItemsPerPage());
        $this->assertEquals(1, $this->restSearchAttributesTransfer->getPagination()->getMaxPage());
        $this->assertEquals(3, $this->restSearchAttributesTransfer->getPagination()->getNumFound());

        $this->assertCount(1, $this->restSearchAttributesTransfer->getValueFacets());
        $this->assertSame('label', $this->restSearchAttributesTransfer->getValueFacets()[0]['name']);
        $this->assertCount(1, $this->restSearchAttributesTransfer->getRangeFacets());
        $this->assertSame('rating', $this->restSearchAttributesTransfer->getRangeFacets()[0]['name']);
        $this->assertArrayHasKey('config', $this->restSearchAttributesTransfer->getValueFacets()[0]);
        $this->assertArrayHasKey('config', $this->restSearchAttributesTransfer->getRangeFacets()[0]);

        foreach ($this->restSearchAttributesTransfer->getValueFacets() as $valueFacet) {
            $this->assertArrayHasKey('parameterName', $valueFacet->getConfig());
            $this->assertArrayHasKey('isMultiValued', $valueFacet->getConfig());
            $this->assertIsString($valueFacet->getConfig()->getParameterName());
            $this->assertIsBool($valueFacet->getConfig()->getIsMultiValued());
        }

        foreach ($this->restSearchAttributesTransfer->getRangeFacets() as $rangeFacet) {
            $this->assertArrayHasKey('parameterName', $rangeFacet->getConfig());
            $this->assertArrayHasKey('isMultiValued', $rangeFacet->getConfig());
            $this->assertIsString($rangeFacet->getConfig()->getParameterName());
            $this->assertIsBool($rangeFacet->getConfig()->getIsMultiValued());
        }
    }

    /**
     * @return void
     */
    public function testMapperWillReturnEmptyAttributesData(): void
    {
        $this->restSearchAttributesTransfer = $this
            ->catalogSearchResourceMapper
            ->mapSearchResultToRestAttributesTransfer(
                $this->mockEmptyRestSearchResponseTransfer()
            );

        $this->assertEmpty($this->getProductsFromRestCatalogSearchAttributesTransfer());
    }

    /**
     * @return \ArrayObject
     */
    protected function getProductsFromRestCatalogSearchAttributesTransfer(): ArrayObject
    {
        return isset($this->restSearchAttributesTransfer[static::KEY_ABSTRACT_PRODUCTS])
            ? $this->restSearchAttributesTransfer[static::KEY_ABSTRACT_PRODUCTS]
            : $this->restSearchAttributesTransfer[static::KEY_PRODUCTS];
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
        $mockRestSearchResponse['facets'] = $this->mockFacets();

        return $mockRestSearchResponse;
    }

    /**
     * @return void
     */
    protected function mockCurrencyClient(): void
    {
        $this->currencyClientMock = $this->getMockBuilder(CurrencyClient::class)->getMock();
        $currencyTransfer = new CurrencyTransfer();
        $currencyTransfer->setCode(static::REQUESTED_CURRENCY);
        $this->currencyClientMock->method('getCurrent')->willReturn($currencyTransfer);
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

    /**
     * @return array
     */
    protected function mockFacets(): array
    {
        $pagination = [];
        $pagination['label'] = $this->mockLabelFacetSearchResult();
        $pagination['rating'] = $this->mockRatingFacetSearchResult();

        return $pagination;
    }

    /**
     * @return \Generated\Shared\Transfer\FacetSearchResultTransfer
     */
    protected function mockLabelFacetSearchResult(): FacetSearchResultTransfer
    {
        $facetSearchResultTransfer = new FacetSearchResultTransfer();
        $facetSearchResultTransfer->setName('label');
        $facetSearchResultTransfer->setDocCount(null);
        $facetSearchResultTransfer->setActiveValue(null);

        $facetSearchResultValue = new FacetSearchResultValueTransfer();
        $facetSearchResultValue->setDocCount(17);
        $facetSearchResultValue->setValue('SALE %');
        $facetSearchResultTransfer->addValue($facetSearchResultValue);

        $facetSearchResultValue = new FacetSearchResultValueTransfer();
        $facetSearchResultValue->setDocCount(7);
        $facetSearchResultValue->setValue('Standard Label');
        $facetSearchResultTransfer->addValue($facetSearchResultValue);

        $facetConfig = new FacetConfigTransfer();
        $facetConfig->setName('label');
        $facetConfig->setParameterName('label');
        $facetConfig->setShortParameterName(null);
        $facetConfig->setFieldName('string-facet');
        $facetConfig->setType('enumeration');
        $facetConfig->setIsMultiValued(true);
        $facetConfig->setValueTransformer('Spryker\Client\ProductLabelStorage\Plugin\ProductLabelFacetValueTransformerPlugin');
        $facetSearchResultTransfer->setConfig($facetConfig);

        return $facetSearchResultTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\RangeSearchResultTransfer
     */
    protected function mockRatingFacetSearchResult(): RangeSearchResultTransfer
    {
        $facetSearchResultTransfer = new RangeSearchResultTransfer();
        $facetSearchResultTransfer->setName('rating');
        $facetSearchResultTransfer->setDocCount(null);
        $facetSearchResultTransfer->setMin(400);
        $facetSearchResultTransfer->setMax(467);
        $facetSearchResultTransfer->setActiveMin(400);
        $facetSearchResultTransfer->setActiveMax(467);

        $facetConfig = new FacetConfigTransfer();
        $facetConfig->setName('rating');
        $facetConfig->setParameterName('rating');
        $facetConfig->setShortParameterName(null);
        $facetConfig->setFieldName('integer-facet');
        $facetConfig->setType('range');
        $facetConfig->setIsMultiValued(null);
        $facetConfig->setValueTransformer('Spryker\Client\ProductReview\Plugin\ProductRatingValueTransformer');
        $facetSearchResultTransfer->setConfig($facetConfig);

        return $facetSearchResultTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\PriceModeConfigurationTransfer
     */
    protected function getPriceModeInformation()
    {
        return (new PriceModeConfigurationTransfer())
            ->setCurrentPriceMode(static::GROSS_MODE)
            ->setGrossModeIdentifier(static::GROSS_MODE)
            ->setNetModeIdentifier(static::NET_MODE);
    }
}

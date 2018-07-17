<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\SearchRestApi\Processor\Mapper;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\FacetConfigTransfer;
use Generated\Shared\Transfer\FacetSearchResultTransfer;
use Generated\Shared\Transfer\FacetSearchResultValueTransfer;
use Generated\Shared\Transfer\PaginationSearchResultTransfer;
use Generated\Shared\Transfer\RangeSearchResultTransfer;
use Generated\Shared\Transfer\RestSearchAttributesTransfer;
use Generated\Shared\Transfer\SortSearchResultTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilder;
use Spryker\Glue\SearchRestApi\Processor\Mapper\SearchResourceMapper;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Glue
 * @group SearchRestApi
 * @group Processor
 * @group Mapper
 * @group AbstractMapperTest
 * Add your own group annotations below this line
 */
abstract class AbstractMapperTest extends Unit
{
    /**
     * @var \Spryker\Glue\SearchRestApi\Processor\Mapper\SearchResourceMapper
     */
    protected $searchResourceMapper;

    /**
     * @var \Generated\Shared\Transfer\RestSearchAttributesTransfer
     */
    protected $restSearchAttributesTransfer;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->restSearchAttributesTransfer = new RestSearchAttributesTransfer();
        $this->searchResourceMapper = new SearchResourceMapper(new RestResourceBuilder());
    }

    /**
     * @return void
     */
    abstract public function testMapperWillReturnRestResponseWithNotEmptyAttributesData();

    /**
     * @return void
     */
    abstract public function testMapperWillReturnRestResponseWithEmptyAttributesData();

    /**
     * @return array
     */
    protected function mockRestSearchResponseTransfer()
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
    protected function mockEmptyRestSearchResponseTransfer()
    {
        $mockRestSearchResponse = [];
        $mockRestSearchResponse['products'] = [];
        $mockRestSearchResponse['sort'] = $this->mockSort();
        $mockRestSearchResponse['pagination'] = $this->mockPagination();
        $mockRestSearchResponse['spellingSuggestion'] = 'cameras';

        return $mockRestSearchResponse;
    }

    /**
     * @return \Generated\Shared\Transfer\FacetSearchResultTransfer
     */
    protected function mockCategoryFacetSearchResultTransfer()
    {
        $facetTransfer = new FacetSearchResultTransfer();
        $facetTransfer->setName("category");
        $facetTransfer->setValues(new ArrayObject([
                "flag::STD_PROP_LIST" => false,
                "flag::ARRAY_AS_PROPS" => false,
                "iteratorClass" => "ArrayIterator",
                "storage" => [
                    (new FacetSearchResultValueTransfer())->fromArray([
                            "value" => 1,
                            "docCount" => 3,
                        ]),
                ],
            ]));

        return $facetTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\RangeSearchResultTransfer
     */
    protected function mockRatingRangeSearchResultTransfer()
    {
        $facetTransfer = new RangeSearchResultTransfer();
        $facetTransfer->fromArray([
            "name" => "rating",
            "min" => 0,
            "max" => 0,
            "activeMin" => 0,
            "activeMax" => 0,
            "config" => (new FacetConfigTransfer())->fromArray([
                "name" => "rating",
                "parameterName" => "rating",
                "shortParameterName" => null,
                "fieldName" => "integer-facet",
                "type" => "range",
                "isMultiValued" => null,
                "size" => null,
                "valueTransformer" => "Spryker\Client\ProductReview\Plugin\ProductRatingValueTransformer",
                "aggregationParams" => [],
            ]),
            "docCount" => null,
        ]);

        return $facetTransfer;
    }

    /**
     * @return array
     */
    protected function mockFacets()
    {
        $facets = [];
        $facets['category'] = $this->mockCategoryFacetSearchResultTransfer();
        $facets['rating'] = $this->mockRatingRangeSearchResultTransfer();

        return $facets;
    }

    /**
     * @return array
     */
    protected function mockProducts()
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
        $products[] = [
            "images" => [
                "fk_product_image_set" => 421,
                "id_product_image" => 202,
                "external_url_small" => "//images.icecat.biz/img/norm/low/15721464-9569.jpg",
                "external_url_large" => "//images.icecat.biz/img/norm/high/15721464-9569.jpg",
                "id_product_image_set_to_product_image" => 421,
                "fk_product_image" => 202,
            ],
            "id_product_labels" => [
                0 => 2,
            ],
            "price" => 12572,
            "abstract_name" => "Toshiba CAMILEO P20",
            "id_product_abstract" => 207,
            "type" => "product_abstract",
            "prices" => [
                "DEFAULT" => 12572,
            ],
            "abstract_sku" => "207",
            "url" => "/en/toshiba-camileo-p20-207",
        ];
        $products[] = [
            "images" => [
                0 => [
                    "fk_product_image_set" => 422,
                    "id_product_image" => 203,
                    "external_url_small" => "//images.icecat.biz/img/norm/low/14678762-7696.jpg",
                    "external_url_large" => "//images.icecat.biz/img/norm/high/14678762-7696.jpg",
                    "id_product_image_set_to_product_image" => 422,
                    "fk_product_image" => 203,
                ],
            ],
            "id_product_labels" => [
                0 => 2,
            ],
            "price" => 34668,
            "abstract_name" => "Toshiba CAMILEO P20",
            "id_product_abstract" => 208,
            "type" => "product_abstract",
            "prices" => [
                "DEFAULT" => 34668,
            ],
            "abstract_sku" => "208",
            "url" => "/en/toshiba-camileo-p20-208",
        ];

        return $products;
    }

    /**
     * @return \Generated\Shared\Transfer\SortSearchResultTransfer
     */
    protected function mockSort()
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
    protected function mockPagination()
    {
        $pagination = new PaginationSearchResultTransfer();
        $pagination->setNumFound(3);
        $pagination->setCurrentItemsPerPage(12);
        $pagination->setCurrentPage(1);
        $pagination->setMaxPage(1);

        return $pagination;
    }
}

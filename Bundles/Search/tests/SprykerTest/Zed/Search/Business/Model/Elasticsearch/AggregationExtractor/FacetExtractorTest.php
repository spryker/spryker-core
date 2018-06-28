<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Search\Business\Model\Elasticsearch\AggregationExtractor;

use Codeception\Test\Unit;
use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\FacetConfigTransfer;
use Spryker\Client\Search\Model\Elasticsearch\Aggregation\StringFacetAggregation;
use Spryker\Client\Search\Model\Elasticsearch\AggregationExtractor\FacetExtractor;
use Spryker\Client\Search\Model\Elasticsearch\AggregationExtractor\FacetValueTransformerFactoryInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Search
 * @group Business
 * @group Model
 * @group Elasticsearch
 * @group AggregationExtractor
 * @group FacetExtractorTest
 * Add your own group annotations below this line
 */
class FacetExtractorTest extends Unit
{
    /**
     * @return void
     */
    public function testExtractDataFromAggregations()
    {
        $testFieldName = 'field1';

        $facetConfigTransfer = (new FacetConfigTransfer())
            ->setName($testFieldName)
            ->setParameterName($testFieldName)
            ->setFieldName(PageIndexMap::STRING_FACET);

        $facetValueTransformerFactory = $this->getMockBuilder(FacetValueTransformerFactoryInterface::class)->getMock();
        $facetExtractor = new FacetExtractor(
            $facetConfigTransfer,
            $facetValueTransformerFactory
        );

        /** @var \Generated\Shared\Transfer\FacetSearchResultTransfer $facetResultTransfer */
        $facetResultTransfer = $facetExtractor->extractDataFromAggregations($this->getAggregationFixture($testFieldName), []);

        $this->assertEquals($facetResultTransfer->getValues()[0]->getValue(), 1);
        $this->assertEquals($facetResultTransfer->getValues()[0]->getDocCount(), 10);
        $this->assertEquals($facetResultTransfer->getValues()[1]->getValue(), 3);
        $this->assertEquals($facetResultTransfer->getValues()[1]->getDocCount(), 30);
    }

    /**
     * @return void
     */
    public function testExtractDataFromAggregationsStandaloneAggregation()
    {
        $testFieldName = 'field1';

        $facetConfigTransfer = (new FacetConfigTransfer())
            ->setName($testFieldName)
            ->setParameterName($testFieldName)
            ->setFieldName(PageIndexMap::STRING_FACET)
            ->setAggregationParams([
                StringFacetAggregation::AGGREGATION_PARAM_SIZE => 10,
            ]);

        $facetValueTransformerFactory = $this->getMockBuilder(FacetValueTransformerFactoryInterface::class)->getMock();
        $facetExtractor = new FacetExtractor(
            $facetConfigTransfer,
            $facetValueTransformerFactory
        );

        /** @var \Generated\Shared\Transfer\FacetSearchResultTransfer $facetResultTransfer */
        $facetResultTransfer = $facetExtractor->extractDataFromAggregations($this->getStandaloneAggregationFixture($testFieldName), []);

        $this->assertEquals($facetResultTransfer->getValues()[0]->getValue(), 1);
        $this->assertEquals($facetResultTransfer->getValues()[0]->getDocCount(), 10);
        $this->assertEquals($facetResultTransfer->getValues()[1]->getValue(), 3);
        $this->assertEquals($facetResultTransfer->getValues()[1]->getDocCount(), 30);
    }

    /**
     * @param string $fieldName
     *
     * @return array
     */
    protected function getStandaloneAggregationFixture($fieldName)
    {
        return [
            "doc_count" => 50,
            PageIndexMap::STRING_FACET . StringFacetAggregation::PATH_SEPARATOR . $fieldName . StringFacetAggregation::NAME_SUFFIX => [
                "doc_count" => 40,
                PageIndexMap::STRING_FACET . StringFacetAggregation::PATH_SEPARATOR . $fieldName . StringFacetAggregation::VALUE_SUFFIX => [
                    "buckets" => [
                        [
                            "key" => "1",
                            "doc_count" => 10,
                        ],
                        [
                            "key" => "3",
                            "doc_count" => 30,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param string $fieldName
     *
     * @return array
     */
    protected function getAggregationFixture($fieldName)
    {
        return [
            "doc_count" => 50,
            PageIndexMap::STRING_FACET . StringFacetAggregation::NAME_SUFFIX => [
                "buckets" => [
                    [
                        "key" => $fieldName,
                        "doc_count" => 40,
                        PageIndexMap::STRING_FACET . StringFacetAggregation::VALUE_SUFFIX => [
                            "buckets" => [
                                [
                                    "key" => "1",
                                    "doc_count" => 10,
                                ],
                                [
                                    "key" => "3",
                                    "doc_count" => 30,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}

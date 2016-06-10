<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\AggregationExtractor;

use Generated\Shared\Transfer\FacetConfigTransfer;
use Generated\Shared\Transfer\FacetSearchResultTransfer;
use Generated\Shared\Transfer\FacetSearchResultValueTransfer;
use Spryker\Client\Search\Model\Elasticsearch\Aggregation\StringFacetAggregation;

class FacetExtractor implements AggregationExtractorInterface
{

    /**
     * @var \Generated\Shared\Transfer\FacetConfigTransfer
     */
    protected $facetConfigTransfer;

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     */
    public function __construct(FacetConfigTransfer $facetConfigTransfer)
    {
        $this->facetConfigTransfer = $facetConfigTransfer;
    }

    /**
     * @param array $aggregations
     * @param array $requestParameters
     *
     * @return \Spryker\Shared\Transfer\TransferInterface
     */
    public function extractDataFromAggregations(array $aggregations, array $requestParameters)
    {
        $parameterName = $this->facetConfigTransfer->getParameterName();
        $fieldName = $this->facetConfigTransfer->getFieldName();

        $facetResultValueTransfers = $this->extractFacetData($aggregations, $parameterName, $fieldName);

        $facetResultTransfer = new FacetSearchResultTransfer();
        $facetResultTransfer
            ->setName($parameterName)
            ->setValues($facetResultValueTransfers);

        if (isset($requestParameters[$parameterName])) {
            $facetResultTransfer->setActiveValue($requestParameters[$parameterName]);
        }

        return $facetResultTransfer;
    }

    /**
     * @param array $aggregation
     * @param string $parameterName
     * @param string $fieldName
     *
     * @return \ArrayObject
     */
    protected function extractFacetData(array $aggregation, $parameterName, $fieldName)
    {
        $facetResultValues = new \ArrayObject();

        foreach ($aggregation[$fieldName . StringFacetAggregation::NAME_SUFFIX]['buckets'] as $nameBucket) {
            if ($nameBucket['key'] !== $parameterName) {
                continue;
            }

            foreach ($nameBucket[$fieldName . StringFacetAggregation::VALUE_SUFFIX]['buckets'] as $valueBucket) {
                $facetResultValueTransfer = new FacetSearchResultValueTransfer();
                $facetResultValueTransfer
                    ->setValue($valueBucket['key'])
                    ->setDocCount($valueBucket['doc_count']);

                $facetResultValues->append($facetResultValueTransfer);
            }

            break;
        }

        return $facetResultValues;
    }

}

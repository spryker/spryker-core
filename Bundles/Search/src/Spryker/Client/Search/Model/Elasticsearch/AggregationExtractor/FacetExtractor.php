<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\AggregationExtractor;

use ArrayObject;
use Generated\Shared\Transfer\FacetConfigTransfer;
use Generated\Shared\Transfer\FacetSearchResultTransfer;
use Generated\Shared\Transfer\FacetSearchResultValueTransfer;
use Spryker\Client\Search\Model\Elasticsearch\Aggregation\StringFacetAggregation;

class FacetExtractor implements AggregationExtractorInterface
{

    const PATH_SEPARATOR = '.';

    /**
     * @var \Generated\Shared\Transfer\FacetConfigTransfer
     */
    protected $facetConfigTransfer;

    /**
     * @var \Spryker\Client\Search\Model\Elasticsearch\AggregationExtractor\FacetValueTransformerFactoryInterface
     */
    protected $facetValueTransformerFactory;

    /**
     * @var \Spryker\Client\Search\Dependency\Plugin\FacetSearchResultValueTransformerPluginInterface|null
     */
    protected $valueTransformerPlugin;

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param \Spryker\Client\Search\Model\Elasticsearch\AggregationExtractor\FacetValueTransformerFactoryInterface $facetValueTransformerFactory
     */
    public function __construct(FacetConfigTransfer $facetConfigTransfer, FacetValueTransformerFactoryInterface $facetValueTransformerFactory)
    {
        $this->facetConfigTransfer = $facetConfigTransfer;
        $this->facetValueTransformerFactory = $facetValueTransformerFactory;
        $this->valueTransformerPlugin = $facetValueTransformerFactory->createTransformer($facetConfigTransfer);
    }

    /**
     * @param array $aggregations
     * @param array $requestParameters
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function extractDataFromAggregations(array $aggregations, array $requestParameters)
    {
        $parameterName = $this->facetConfigTransfer->getParameterName();
        $fieldName = $this->facetConfigTransfer->getFieldName();

        $facetResultValueTransfers = $this->extractFacetData($aggregations, $parameterName, $fieldName);

        $facetResultTransfer = new FacetSearchResultTransfer();
        $facetResultTransfer
            ->setName($parameterName)
            ->setValues($facetResultValueTransfers)
            ->setConfig(clone $this->facetConfigTransfer);

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
        if ($this->facetConfigTransfer->getIsStandalone()) {
            return $this->extractStandaloneFacetDataBuckets($aggregation, $fieldName);
        }

        return $this->extractFacetDataBuckets($aggregation, $parameterName, $fieldName);
    }

    /**
     * @param array $aggregation
     * @param string $parameterName
     * @param string $fieldName
     *
     * @return \ArrayObject
     */
    protected function extractFacetDataBuckets(array $aggregation, $parameterName, $fieldName)
    {
        $facetResultValues = new ArrayObject();
        $nameFieldName = $this->getFieldNameWithNameSuffix($fieldName);
        $valueFieldName = $this->getFieldNameWithValueSuffix($fieldName);

        foreach ($aggregation[$nameFieldName]['buckets'] as $nameBucket) {
            if ($nameBucket['key'] !== $parameterName) {
                continue;
            }

            foreach ($nameBucket[$valueFieldName]['buckets'] as $valueBucket) {
                $facetResultValueTransfer = new FacetSearchResultValueTransfer();
                $value = $this->getFacetValue($valueBucket);

                $facetResultValueTransfer
                    ->setValue($value)
                    ->setDocCount($valueBucket['doc_count']);

                $facetResultValues->append($facetResultValueTransfer);
            }

            break;
        }

        return $facetResultValues;
    }

    /**
     * @param array $aggregation
     * @param string $fieldName
     *
     * @return \ArrayObject
     */
    protected function extractStandaloneFacetDataBuckets(array $aggregation, $fieldName)
    {
        $facetResultValues = new ArrayObject();
        $nestedFieldName = $this->addNestedFieldPrefix($fieldName, $this->facetConfigTransfer->getName());

        $nameFieldName = $this->getFieldNameWithNameSuffix($nestedFieldName);
        $valueFieldName = $this->getFieldNameWithValueSuffix($nestedFieldName);

        foreach ($aggregation[$nameFieldName][$valueFieldName]['buckets'] as $valueBucket) {
            $facetResultValueTransfer = new FacetSearchResultValueTransfer();
            $value = $this->getFacetValue($valueBucket);

            $facetResultValueTransfer
                ->setValue($value)
                ->setDocCount($valueBucket['doc_count']);

            $facetResultValues->append($facetResultValueTransfer);
        }

        return $facetResultValues;
    }

    /**
     * @param string $fieldName
     * @param string $nestedFieldName
     *
     * @return string
     */
    protected function addNestedFieldPrefix($fieldName, $nestedFieldName)
    {
        return $fieldName . static::PATH_SEPARATOR . $nestedFieldName;
    }

    /**
     * @param string $fieldName
     *
     * @return string
     */
    protected function getFieldNameWithNameSuffix($fieldName)
    {
        return $fieldName . StringFacetAggregation::NAME_SUFFIX;
    }

    /**
     * @param string $fieldName
     *
     * @return string
     */
    protected function getFieldNameWithValueSuffix($fieldName)
    {
        return $fieldName . StringFacetAggregation::VALUE_SUFFIX;
    }

    /**
     * @param array $valueBucket
     *
     * @return mixed
     */
    protected function getFacetValue(array $valueBucket)
    {
        $value = $valueBucket['key'];

        if ($this->valueTransformerPlugin) {
            $value = $this->valueTransformerPlugin->transformForDisplay($value);
        }

        return $value;
    }

}

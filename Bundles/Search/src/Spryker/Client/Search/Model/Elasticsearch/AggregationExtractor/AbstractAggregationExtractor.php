<?php


namespace Spryker\Client\Search\Model\Elasticsearch\AggregationExtractor;


use Generated\Shared\Transfer\FacetConfigTransfer;

abstract class AbstractAggregationExtractor implements AggregationExtractorInterface
{

    const PATH_SEPARATOR = '.';

    /**
     * @param array $aggregations
     * @param array $requestParameters
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    abstract public function extractDataFromAggregations(array $aggregations, array $requestParameters);

    /**
     * @param FacetConfigTransfer $facetConfigTransfer
     *
     * @return string
     */
    protected function getNestedFieldName(FacetConfigTransfer $facetConfigTransfer)
    {
        $nestedFieldName = $facetConfigTransfer->getFieldName();

        if ($facetConfigTransfer->getAggregationParams()) {
            $nestedFieldName = $this->addNestedFieldPrefix(
                $nestedFieldName,
                $facetConfigTransfer->getName()
            );
        }

        return $nestedFieldName;
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

}
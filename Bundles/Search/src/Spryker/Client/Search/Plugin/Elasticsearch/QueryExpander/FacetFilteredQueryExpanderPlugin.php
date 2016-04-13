<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Generated\Shared\Transfer\FacetConfigTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Model\Query\QueryInterface;
use Spryker\Client\Search\Plugin\Config\FacetConfigBuilder;
use Spryker\Client\Search\Plugin\QueryExpanderPluginInterface;

/**
 * @method \Spryker\Client\Search\SearchFactory getFactory()
 *
 * TODO: split into specific facet expanders (e.g. slider, etc.)
 */
class FacetFilteredQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{

    /**
     * @param \Spryker\Client\Search\Model\Query\QueryInterface $searchQuery
     * @param array $requestParameters
     *
     * @return \Spryker\Client\Search\Model\Query\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = [])
    {
        $this->addFacetFiltersToQuery($searchQuery->getSearchQuery(), $requestParameters);

        return $searchQuery;
    }

    /**
     * @param \Elastica\Query $query
     * @param array $requestParameters
     *
     * @return void
     */
    protected function addFacetFiltersToQuery(Query $query, array $requestParameters)
    {
        $boolQuery = $query->getQuery();
        if (!$boolQuery instanceof BoolQuery) {
            throw new \InvalidArgumentException(sprintf('Facet filters available only with %s, got: %s', BoolQuery::class, get_class($boolQuery)));
        }

        $facetConfig = $this
            ->getFactory()
            ->getSearchConfig()
            ->getFacetConfigBuilder();

        $facetConfigTransfers = $facetConfig->getActive($requestParameters);

        if ($facetConfigTransfers) {
            foreach ($facetConfigTransfers as $facetConfigTransfer) {
                $this->addFacetFilter($boolQuery, $facetConfigTransfer, $requestParameters);
            }
        }
    }

    /**
     * @param \Elastica\Query\BoolQuery $boolQuery
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param array $requestParameters
     *
     * @return void
     */
    protected function addFacetFilter(BoolQuery $boolQuery, FacetConfigTransfer $facetConfigTransfer, array $requestParameters)
    {
        $filterValue = isset($requestParameters[$facetConfigTransfer->getParameterName()]) ? $requestParameters[$facetConfigTransfer->getParameterName()] : null;

        if (trim($filterValue) === '') {
            return;
        }

        $queryObject = $this->createQueryObject($facetConfigTransfer, $filterValue);
        $boolQuery->addFilter($queryObject);
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param string $filterValue
     *
     * @return \Elastica\Query\Range|null
     */
    protected function createQueryObject(FacetConfigTransfer $facetConfigTransfer, $filterValue)
    {
        $nestedQueryBuilder = $this->getFactory()->createNestedQueryBuilder();
        $fieldName = $facetConfigTransfer->getFieldName();
        $filterFacetName = $facetConfigTransfer->getName();

        // sliders will be range queries, lets get min/max values
        if ($facetConfigTransfer->getType() === FacetConfigBuilder::TYPE_RANGE) {
            // TODO: this code is Slider related
            list($minValue, $maxValue) = $this->getMinMaxValue($facetConfigTransfer, $filterValue);

            return $nestedQueryBuilder->createNestedRangeQuery($fieldName, $filterFacetName, $minValue, $maxValue);
        }

        // the rest is either multi-valued or single values
        if (is_array($filterValue)) {
            return $nestedQueryBuilder->createNestedTermsQuery($fieldName, $filterFacetName, $filterValue);
        }

        return $nestedQueryBuilder->createNestedTermQuery($fieldName, $filterFacetName, $filterValue);
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param string $filterValue
     *
     * @return array
     */
    protected function getMinMaxValue(FacetConfigTransfer $facetConfigTransfer, $filterValue)
    {
        $values = explode('-', $filterValue); // TODO: divider into const
        $minValue = $values[0];
        $maxValue = $minValue;

        if (count($values) > 1) {
            $maxValue = $values[1];
        }

        // FIXME: somehow convert price to cents
//        if (isset($facetConfigTransfer[FacetConfig::KEY_VALUE_CALLBACK_BEFORE]) &&
//            is_callable($facetConfigTransfer[FacetConfig::KEY_VALUE_CALLBACK_BEFORE])) {
//            $minValue = call_user_func($facetConfigTransfer[FacetConfig::KEY_VALUE_CALLBACK_BEFORE], $minValue);
//            $maxValue = call_user_func($facetConfigTransfer[FacetConfig::KEY_VALUE_CALLBACK_BEFORE], $maxValue);
//        }
        $minValue = $minValue * 100;
        $maxValue = $maxValue * 100;

        return [$minValue, $maxValue];
    }

}

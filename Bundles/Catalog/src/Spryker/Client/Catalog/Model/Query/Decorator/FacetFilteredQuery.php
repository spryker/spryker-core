<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\Model\Query\Decorator;

use Elastica\Filter\BoolAnd;
use Elastica\Query;
use Spryker\Client\Catalog\Model\Builder\NestedFilterBuilderInterface;
use Spryker\Client\Catalog\Model\FacetConfig;
use Spryker\Client\Search\Model\Query\Decorator\AbstractQueryDecorator;
use Spryker\Client\Search\Model\Query\QueryInterface;

class FacetFilteredQuery extends AbstractQueryDecorator
{

    /**
     * @var \Spryker\Client\Catalog\Model\FacetConfig
     */
    protected $facetConfig;

    /**
     * @var \Spryker\Client\Catalog\Model\Builder\NestedFilterBuilderInterface
     */
    protected $nestedFilterBuilder;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * @param \Spryker\Client\Search\Model\Query\QueryInterface $searchQuery
     * @param \Spryker\Client\Catalog\Model\FacetConfig $facetConfig
     * @param \Spryker\Client\Catalog\Model\Builder\NestedFilterBuilderInterface $nestedFilterBuilder
     * @param array $parameters
     */
    public function __construct(QueryInterface $searchQuery, FacetConfig $facetConfig, NestedFilterBuilderInterface $nestedFilterBuilder, array $parameters) {
        parent::__construct($searchQuery);

        $this->facetConfig = $facetConfig;
        $this->nestedFilterBuilder = $nestedFilterBuilder;
        $this->parameters = $parameters;
    }

    /**
     * @return \Elastica\Query
     */
    public function getSearchQuery()
    {
        return $this->addFacetFiltersToQuery($this->searchQuery->getSearchQuery());
    }

    /**
     * @param \Elastica\Query $query
     *
     * @return \Elastica\Query
     */
    protected function addFacetFiltersToQuery(Query $query)
    {
        $filters = array_intersect(
            array_keys($this->parameters),
            $this->facetConfig->getAllParamNamesForFacets(true)
        );
        if ($filters) {
            $filterObjects = new BoolAnd();
            foreach ($filters as $filter) {
                $facetConfig = $this->facetConfig->getFacetSetupFromParameter($filter);
                $filterFacetName = $this->facetConfig->getFacetNameFromParameter($filter);
                $filterValue = isset($this->parameters[$filter]) ? $this->parameters[$filter] : null;

                if (trim($filterValue) === '') {
                    continue;
                }
                $filterObject = $this->createFilterObject($facetConfig, $filterValue, $filterFacetName);
                $filterObjects->addFilter($filterObject);
            }
            $query->setPostFilter($filterObjects);
        }

        return $query;
    }

    /**
     * @param array $facetConfig
     * @param string $filterValue
     * @param string $filterFacetName
     *
     * @return \Elastica\Filter\Range|null
     */
    protected function createFilterObject($facetConfig, $filterValue, $filterFacetName)
    {
        $fieldName = $facetConfig[FacetConfig::KEY_FACET_FIELD_NAME];
        //sliders will be range queries, lets get min/max values
        if ($facetConfig[FacetConfig::KEY_TYPE] === FacetConfig::TYPE_SLIDER) {
            list($minValue, $maxValue) = $this->getMinMaxValue($facetConfig, $filterValue);

            return $this->nestedFilterBuilder->createNestedRangeFilter($fieldName, $filterFacetName, $minValue, $maxValue);
        } elseif (is_array($filterValue)) {
            //the rest is either multi-valued or single values
            return $this->nestedFilterBuilder->createNestedTermsFilter($fieldName, $filterFacetName, $filterValue);
        } else {
            return $this->nestedFilterBuilder->createNestedTermFilter($fieldName, $filterFacetName, $filterValue);
        }
    }

    /**
     * @param array $facetConfig
     * @param string $filterValue
     *
     * @return array
     */
    protected function getMinMaxValue(array $facetConfig, $filterValue)
    {
        $values = explode($facetConfig[FacetConfig::KEY_RANGE_DIVIDER], $filterValue);
        $minValue = $values[0];
        $maxValue = $minValue;
        if (count($values) > 1) {
            $maxValue = $values[1];
        }
        if (isset($facetConfig[FacetConfig::KEY_VALUE_CALLBACK_BEFORE]) &&
            is_callable($facetConfig[FacetConfig::KEY_VALUE_CALLBACK_BEFORE])) {
            $minValue = call_user_func($facetConfig[FacetConfig::KEY_VALUE_CALLBACK_BEFORE], $minValue);
            $maxValue = call_user_func($facetConfig[FacetConfig::KEY_VALUE_CALLBACK_BEFORE], $maxValue);

            return [$minValue, $maxValue];
        }

        return [$minValue, $maxValue];
    }

}

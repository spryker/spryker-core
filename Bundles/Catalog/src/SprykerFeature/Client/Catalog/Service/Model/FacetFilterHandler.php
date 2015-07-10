<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Catalog\Service\Model;

use Elastica\Filter\BoolAnd;
use Elastica\Filter;
use Elastica\Query;
use SprykerFeature\Client\Catalog\Service\Model\Builder\NestedFilterBuilderInterface;
use Symfony\Component\HttpFoundation\Request;

class FacetFilterHandler implements FacetFilterHandlerInterface
{

    /**
     * @var NestedFilterBuilderInterface
     */
    protected $nestedFilterBuilder;

    /**
     * @var FacetConfig
     */
    protected $facetConfig;

    /**
     * @param NestedFilterBuilderInterface $nestedFilterBuilder
     * @param FacetConfig $facetConfig
     */
    public function __construct(
        NestedFilterBuilderInterface $nestedFilterBuilder,
        FacetConfig $facetConfig
    ) {
        $this->nestedFilterBuilder = $nestedFilterBuilder;
        $this->facetConfig = $facetConfig;
    }

    /**
     * @param Query $query
     * @param Request $request
     */
    public function addFacetFiltersToQuery(Query $query, Request $request)
    {
        $filters = array_intersect(
            $request->query->keys(),
            $this->facetConfig->getAllParamNamesForFacets(true)
        );
        if ($filters) {
            $filterObjects = new BoolAnd();
            foreach ($filters as $filter) {
                $facetConfig = $this->facetConfig->getFacetSetupFromParameter($filter);
                $filterFacetName = $this->facetConfig->getFacetNameFromParameter($filter);
                $filterValue = $request->query->get($filter);

                if (trim($filterValue) === '') {
                    continue;
                }
                $filterObject = $this->createFilterObject($facetConfig, $filterValue, $filterFacetName);
                $filterObjects->addFilter($filterObject);
            }
            $query->setPostFilter($filterObjects);
        }
    }

    /**
     * @param array $facetConfig
     * @param string $filterValue
     * @param string $filterFacetName
     *
     * @return Filter\Range|null
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

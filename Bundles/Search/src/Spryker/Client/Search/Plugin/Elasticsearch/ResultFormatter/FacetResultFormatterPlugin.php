<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin\Elasticsearch\ResultFormatter;

use Elastica\ResultSet;
use Generated\Shared\Search\Catalog\PageIndexMap;
use Spryker\Client\Search\Plugin\Config\FacetConfigBuilder;
use Spryker\Client\Search\Plugin\Config\FacetConfigBuilderInterface;

/**
 * @method \Spryker\Client\Search\SearchFactory getFactory()
 */
class FacetResultFormatterPlugin extends AbstractElasticsearchResultFormatterPlugin
{

    /**
     * @param \Elastica\ResultSet $searchResult
     * @param array $requestParameters
     *
     * @return array
     */
    protected function formatSearchResult(ResultSet $searchResult, array $requestParameters)
    {
        return [
            'facets' => $this->extractFacetDataFromResult($searchResult, $requestParameters),
        ];
    }

    /**
     * @param \Elastica\ResultSet $resultSet
     * @param array $requestParameters
     *
     * @return array
     */
    protected function extractFacetDataFromResult(ResultSet $resultSet, array $requestParameters)
    {
        $facetConfig = $this
            ->getFactory()
            ->getSearchConfig()
            ->getFacetConfigBuilder();

        $facetFields = $this->getFacetFieldNames($facetConfig);
        $numericFacetFields = $this->getNumericFacetFieldNames($facetConfig);

        $aggregations = $resultSet->getAggregations();
        
        $facetExtractor = $this->getFactory()->createFacetExtractor();
        $rangeExtractor = $this->getFactory()->createRangeExtractor();

        $facets = $facetExtractor->extractDataFromAggregations($aggregations, $facetFields);
        $ranges = $rangeExtractor->extractDataFromAggregations($aggregations, $numericFacetFields);

        return $this->createFacetResult($facetConfig, $facets, $ranges, $requestParameters);
    }

    /**
     * @param \Spryker\Client\Search\Plugin\Config\FacetConfigBuilderInterface $facetConfig
     *
     * @return array
     */
    protected function getFacetFieldNames(FacetConfigBuilderInterface $facetConfig)
    {
        $facetFieldNames = [];

        foreach ($facetConfig->getAll() as $facetConfigTransfer) {
            $facetFieldNames[] = $facetConfigTransfer->getFieldName();
        }

        return array_unique($facetFieldNames);
    }

    /**
     * @param \Spryker\Client\Search\Plugin\Config\FacetConfigBuilderInterface $facetConfig
     *
     * @return array
     */
    protected function getNumericFacetFieldNames(FacetConfigBuilderInterface $facetConfig)
    {
        $numericFacetFieldNames = [];

        // FIXME: PageIndexMap needs to be removed from Search bundle OR PageIndexMap partially needs to come from this bundle 
        $mapping = new PageIndexMap();
        foreach ($facetConfig->getAll() as $facetConfigTransfer) {
            $facetValueFieldName = $facetConfigTransfer->getFieldName() . '.facet-value';

            if (in_array($mapping->getType($facetValueFieldName), ['integer', 'float'])) {
                $numericFacetFieldNames[] = $facetConfigTransfer->getFieldName();
            }
        }

        return $numericFacetFieldNames;
    }

    /**
     * @param \Spryker\Client\Search\Plugin\Config\FacetConfigBuilderInterface $facetConfig
     * @param array $facets
     * @param array $ranges
     * @param array $requestParameters
     *
     * @return array
     */
    protected function createFacetResult(FacetConfigBuilderInterface $facetConfig, array $facets, array $ranges, array $requestParameters)
    {
        $preparedFacets = [];
        foreach ($facetConfig->getAll() as $currentFacetName => $facetConfigTransfer) {
            $paramName = $facetConfigTransfer->getParameterName();
            if (isset($facets[$currentFacetName])) {
                $currentFacet = [
                    'name' => $paramName,
//                    'config' => $facetConfigTransfer->toArray(),
                    'values' => $facets[$currentFacetName],
                ];

                if (isset($requestParameters[$paramName])) {
                    $currentFacet['activeValue'] = $requestParameters[$paramName];
                }

                // FIXME: this shouldn't be here
                if ($facetConfigTransfer->getType() === FacetConfigBuilder::TYPE_RANGE) {
                    $currentFacet['rangeValues'] = $ranges[$currentFacetName];
                }

                $preparedFacets[$currentFacetName] = $currentFacet;
            }
        }

        return $preparedFacets;
    }

}

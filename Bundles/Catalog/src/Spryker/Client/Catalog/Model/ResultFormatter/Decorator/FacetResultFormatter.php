<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\Model\ResultFormatter\Decorator;

use Elastica\ResultSet;
use Spryker\Client\Catalog\Model\Extractor\AggregationExtractorInterface;
use Spryker\Client\Catalog\Model\FacetConfig;
use Spryker\Client\Search\Model\ResultFormatter\AbstractElasticsearchResultFormatter;
use Spryker\Client\Search\Model\ResultFormatter\Decorator\AbstractElasticsearchResultFormatterDecorator;

class FacetResultFormatter extends AbstractElasticsearchResultFormatterDecorator
{

    /**
     * @var \Spryker\Client\Catalog\Model\FacetConfig
     */
    protected $facetConfig;

    /**
     * @var \Spryker\Client\Catalog\Model\Extractor\AggregationExtractorInterface
     */
    protected $facetExtractor;

    /**
     * @var \Spryker\Client\Catalog\Model\Extractor\AggregationExtractorInterface
     */
    protected $rangeExtractor;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * @param \Spryker\Client\Search\Model\ResultFormatter\AbstractElasticsearchResultFormatter $resultFormatter
     * @param \Spryker\Client\Catalog\Model\FacetConfig $facetConfig
     * @param \Spryker\Client\Catalog\Model\Extractor\AggregationExtractorInterface $facetExtractor
     * @param \Spryker\Client\Catalog\Model\Extractor\AggregationExtractorInterface $rangeExtractor
     * @param array $parameters
     */
    public function __construct(
        AbstractElasticsearchResultFormatter $resultFormatter,
        FacetConfig $facetConfig,
        AggregationExtractorInterface $facetExtractor,
        AggregationExtractorInterface $rangeExtractor,
        array $parameters
    ) {
        parent::__construct($resultFormatter);

        $this->facetConfig = $facetConfig;
        $this->facetExtractor = $facetExtractor;
        $this->rangeExtractor = $rangeExtractor;
        $this->parameters = $parameters;
    }

    /**
     * @param \Elastica\ResultSet $searchResult
     *
     * @return array
     */
    protected function process(ResultSet $searchResult)
    {
        return $this->addFacetResult($searchResult, $this->resultFormatter->formatResult($searchResult));
    }

    /**
     * @param \Elastica\ResultSet $searchResult
     * @param array $result
     *
     * @return array
     */
    protected function addFacetResult(ResultSet $searchResult, array $result)
    {
        $result['numFound'] = $this->extractFacetDataFromResult($searchResult);

        return $result;
    }

    /**
     * @param \Elastica\ResultSet $resultSet
     *
     * @return array
     */
    protected function extractFacetDataFromResult(ResultSet $resultSet)
    {
        $facetFields = $this->facetConfig->getFacetFields();
        $numericFacetFields = $this->facetConfig->getNumericFacetFields();

        $aggregations = $resultSet->getAggregations();
        $facets = $this->facetExtractor->extractDataFromAggregations($aggregations, $facetFields);
        $ranges = $this->rangeExtractor->extractDataFromAggregations($aggregations, $numericFacetFields);

        return $this->createFacetResult($facets, $ranges);
    }

    /**
     * @param array $facets
     * @param array $ranges
     *
     * @return array
     */
    protected function createFacetResult(array $facets, array $ranges)
    {
        $preparedFacets = [];
        foreach ($this->facetConfig->getActiveFacets() as $currentFacetName => $facetConfig) {
            $paramName = $facetConfig[FacetConfig::KEY_PARAM];
            if (isset($facets[$currentFacetName])) {
                $currentFacet = [
                    'name' => $paramName,
                    'config' => $facetConfig,
                    'values' => $facets[$currentFacetName],
                ];
                if (isset($this->parameters[$paramName])) {
                    $currentFacet['activeValue'] = $this->parameters[$paramName];
                }
                if ($facetConfig[FacetConfig::KEY_TYPE] === FacetConfig::TYPE_SLIDER) {
                    $currentFacet['rangeValues'] = $ranges[$currentFacetName];
                }
                $preparedFacets[$currentFacetName] = $currentFacet;
            }
        }

        return $preparedFacets;
    }

}

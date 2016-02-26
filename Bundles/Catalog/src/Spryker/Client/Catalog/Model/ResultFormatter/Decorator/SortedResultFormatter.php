<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\Model\ResultFormatter\Decorator;

use Elastica\ResultSet;
use Spryker\Client\Catalog\Model\FacetConfig;
use Spryker\Client\Search\Model\ResultFormatter\AbstractElasticsearchResultFormatter;
use Spryker\Client\Search\Model\ResultFormatter\Decorator\AbstractElasticsearchResultFormatterDecorator;

class SortedResultFormatter extends AbstractElasticsearchResultFormatterDecorator
{

    /**
     * @var \Spryker\Client\Catalog\Model\FacetConfig
     */
    protected $facetConfig;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * @param \Spryker\Client\Search\Model\ResultFormatter\AbstractElasticsearchResultFormatter $resultFormatter
     * @param \Spryker\Client\Catalog\Model\FacetConfig $facetConfig
     * @param array $parameters
     */
    public function __construct(AbstractElasticsearchResultFormatter $resultFormatter, FacetConfig $facetConfig, array $parameters)
    {
        parent::__construct($resultFormatter);

        $this->facetConfig = $facetConfig;
        $this->parameters = $parameters;
    }

    /**
     * @param \Elastica\ResultSet $searchResult
     *
     * @return array
     */
    protected function process(ResultSet $searchResult)
    {
        return $this->addSortResult($this->resultFormatter->formatResult($searchResult));
    }

    /**
     * @param array $result
     *
     * @return array
     */
    protected function addSortResult(array $result)
    {
        $result['sortNames'] = array_keys($this->facetConfig->getActiveSortAttributes());
        // TODO: Move these outside somehow
        $result['currentSortParam'] = isset($this->parameters['sort']) ? $this->parameters['sort'] : null;
        $result['currentSortOrder'] = isset($this->parameters['sort_order']) ? $this->parameters['sort_order'] : 'asc';

        return $result;
    }

}

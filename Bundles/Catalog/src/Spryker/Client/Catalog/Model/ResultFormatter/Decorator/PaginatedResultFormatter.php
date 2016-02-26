<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\Model\ResultFormatter\Decorator;

use Elastica\ResultSet;
use Spryker\Client\Search\Model\ResultFormatter\AbstractElasticsearchResultFormatter;
use Spryker\Client\Search\Model\ResultFormatter\Decorator\AbstractElasticsearchResultFormatterDecorator;

class PaginatedResultFormatter extends AbstractElasticsearchResultFormatterDecorator
{

    /**
     * @var array
     */
    protected $parameters;

    /**
     * @param \Spryker\Client\Search\Model\ResultFormatter\AbstractElasticsearchResultFormatter $resultFormatter
     * @param array $parameters
     */
    public function __construct(AbstractElasticsearchResultFormatter $resultFormatter, array $parameters)
    {
        parent::__construct($resultFormatter);

        $this->parameters = $parameters;
    }

    /**
     * @param \Elastica\ResultSet $searchResult
     *
     * @return array
     */
    protected function process(ResultSet $searchResult)
    {
        return $this->addPaginationResult($searchResult, $this->resultFormatter->formatResult($searchResult));
    }

    /**
     * @param \Elastica\ResultSet $searchResult
     * @param array $result
     *
     * @return array
     */
    protected function addPaginationResult(ResultSet $searchResult, array $result)
    {
        $currentPage = $this->getCurrentPage();
        $itemsPerPage = $this->getItemsPerPage();

        $result['numFound'] = $searchResult->getTotalHits();
        $result['currentPage'] = $currentPage;
        $result['maxPage'] = ceil($searchResult->getTotalHits() / $itemsPerPage);
        $result['currentItemsPerPage'] = $itemsPerPage;

        return $result;
    }

    /**
     * @return int
     * TODO: add constants
     * TODO: move these methods outside somehow
     */
    protected function getCurrentPage()
    {
        return isset($this->parameters['page']) ? max((int)$this->parameters['page'], 1) : 1;
    }

    /**
     * @return int
     */
    protected function getItemsPerPage()
    {
        return isset($this->parameters['ipp']) ? max((int)$this->parameters['ipp'], 10) : 10;
    }

}

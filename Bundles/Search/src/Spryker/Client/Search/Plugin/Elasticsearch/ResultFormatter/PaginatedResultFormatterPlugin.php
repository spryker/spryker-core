<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin\Elasticsearch\ResultFormatter;

use Elastica\ResultSet;

/**
 * @method \Spryker\Client\Search\SearchFactory getFactory()
 */
class PaginatedResultFormatterPlugin extends AbstractElasticsearchResultFormatterPlugin
{

    /**
     * @param \Elastica\ResultSet $searchResult
     * @param array $requestParameters
     *
     * @return array
     */
    protected function formatSearchResult(ResultSet $searchResult, array $requestParameters)
    {
        $currentPage = $this->getCurrentPage($requestParameters);
        $itemsPerPage = $this->getItemsPerPage($requestParameters);

        $result = [
            'numFound' => $searchResult->getTotalHits(),
            'currentPage' => $currentPage,
            'maxPage' => ceil($searchResult->getTotalHits() / $itemsPerPage),
            'currentItemsPerPage' => $itemsPerPage,
        ];

        return $result;
    }

    /**
     * @param array $requestParameters
     *
     * @return int
     * TODO: add constants
     * TODO: move these methods outside somehow
     */
    protected function getCurrentPage(array $requestParameters)
    {
        return isset($requestParameters['page']) ? max((int)$requestParameters['page'], 1) : 1;
    }

    /**
     * @param array $requestParameters
     *
     * @return int
     */
    protected function getItemsPerPage(array $requestParameters)
    {
        return isset($requestParameters['ipp']) ? max((int)$requestParameters['ipp'], 10) : 10;
    }

}

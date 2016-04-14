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
        $paginationConfig = $this
            ->getFactory()
            ->getSearchConfig()
            ->getPaginationConfigBuilder();

        $currentPage = $paginationConfig->getCurrentPage($requestParameters);
        $itemsPerPage = $paginationConfig->get()->getItemsPerPage();

        $result = [
            'numFound' => $searchResult->getTotalHits(),
            'currentPage' => $currentPage,
            'maxPage' => ceil($searchResult->getTotalHits() / $itemsPerPage),
            'currentItemsPerPage' => $itemsPerPage,
        ];

        return $result;
    }

}

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
class SortedResultFormatterPlugin extends AbstractElasticsearchResultFormatterPlugin
{

    /**
     * @param \Elastica\ResultSet $searchResult
     * @param array $requestParameters
     *
     * @return array
     */
    protected function formatSearchResult(ResultSet $searchResult, array $requestParameters)
    {
        $sortConfig = $this
            ->getFactory()
            ->getSearchConfig()
            ->getSortConfigBuilder();

        $result = [
            'sortNames' => array_keys($sortConfig->getAll()),
            'currentSortParam' => $sortConfig->getActiveParamName($requestParameters),
            'currentSortOrder' => $sortConfig->getActiveSortDirection($requestParameters),
        ];

        return $result;
    }

}

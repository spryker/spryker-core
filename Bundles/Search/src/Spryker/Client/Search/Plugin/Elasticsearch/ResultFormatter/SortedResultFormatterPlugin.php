<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin\Elasticsearch\ResultFormatter;

use Elastica\ResultSet;
use Generated\Shared\Transfer\SortSearchResultTransfer;

/**
 * @method \Spryker\Client\Search\SearchFactory getFactory()
 */
class SortedResultFormatterPlugin extends AbstractElasticsearchResultFormatterPlugin
{

    const NAME = 'sort';

    /**
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }

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

        $sortSearchResultTransfer = new SortSearchResultTransfer();
        $sortSearchResultTransfer
            ->setSortNames(array_keys($sortConfig->getAll()))
            ->setCurrentSortParam($sortConfig->getActiveParamName($requestParameters))
            ->setCurrentSortOrder($sortConfig->getActiveSortDirection($requestParameters));

        return $sortSearchResultTransfer;
    }

}

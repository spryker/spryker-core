<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Plugin\ResultFormatter;

use Elastica\ResultSet;
use Generated\Shared\Transfer\SortSearchResultTransfer;

/**
 * @method \Spryker\Client\SearchElasticsearch\SearchElasticsearchFactory getFactory()
 */
class SortedResultFormatterPlugin extends AbstractElasticsearchResultFormatterPlugin
{
    public const NAME = 'sort';

    /**
     * @return string
     */
    public function getName()
    {
        return static::NAME;
    }

    /**
     * @param \Elastica\ResultSet $searchResult
     * @param array $requestParameters
     *
     * @return \Generated\Shared\Transfer\SortSearchResultTransfer
     */
    protected function formatSearchResult(ResultSet $searchResult, array $requestParameters): SortSearchResultTransfer
    {
        $sortConfig = $this->getFactory()->getSearchConfig()->getSortConfig();
        $sortParamName = $sortConfig->getActiveParamName($requestParameters);
        $sortSearchResultTransfer = new SortSearchResultTransfer();
        $sortSearchResultTransfer
            ->setSortParamNames(array_keys($sortConfig->getAll()))
            ->setCurrentSortParam($sortParamName)
            ->setCurrentSortOrder($sortConfig->getSortDirection($sortParamName));

        return $sortSearchResultTransfer;
    }
}

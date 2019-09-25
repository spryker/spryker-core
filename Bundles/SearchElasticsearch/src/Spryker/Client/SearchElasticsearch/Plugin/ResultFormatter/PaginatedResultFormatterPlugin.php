<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Plugin\ResultFormatter;

use Elastica\ResultSet;
use Generated\Shared\Transfer\PaginationSearchResultTransfer;

/**
 * @method \Spryker\Client\SearchElasticsearch\SearchElasticsearchFactory getFactory()
 */
class PaginatedResultFormatterPlugin extends AbstractElasticsearchResultFormatterPlugin
{
    public const NAME = 'pagination';

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
     * @return \Generated\Shared\Transfer\PaginationSearchResultTransfer
     */
    protected function formatSearchResult(ResultSet $searchResult, array $requestParameters): PaginationSearchResultTransfer
    {
        $paginationConfig = $this
            ->getFactory()
            ->getPaginationConfig();

        $itemsPerPage = $paginationConfig->getCurrentItemsPerPage($requestParameters);
        $maxPage = (int)ceil($searchResult->getTotalHits() / $itemsPerPage);
        $currentPage = min($paginationConfig->getCurrentPage($requestParameters), $maxPage);

        $paginationSearchResultTransfer = new PaginationSearchResultTransfer();
        $paginationSearchResultTransfer
            ->setNumFound($searchResult->getTotalHits())
            ->setCurrentPage($currentPage)
            ->setMaxPage($maxPage)
            ->setCurrentItemsPerPage($itemsPerPage)
            ->setConfig(clone $paginationConfig->get());

        return $paginationSearchResultTransfer;
    }
}

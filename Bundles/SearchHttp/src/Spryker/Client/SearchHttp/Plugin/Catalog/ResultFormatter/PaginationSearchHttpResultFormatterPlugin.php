<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Plugin\Catalog\ResultFormatter;

use Generated\Shared\Transfer\PaginationSearchResultTransfer;
use Generated\Shared\Transfer\SearchHttpResponsePaginationTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface;

/**
 * @method \Spryker\Client\SearchHttp\SearchHttpFactory getFactory()
 */
class PaginationSearchHttpResultFormatterPlugin extends AbstractPlugin implements ResultFormatterPluginInterface
{
    /**
     * @var string
     */
    public const NAME = 'pagination';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getName(): string
    {
        return static::NAME;
    }

    /**
     * {@inheritDoc}
     * - Formats pagination in result.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchHttpResponseTransfer $searchResult
     * @param array<string, mixed> $requestParameters
     *
     * @return \Generated\Shared\Transfer\PaginationSearchResultTransfer
     */
    public function formatResult($searchResult, array $requestParameters = []): PaginationSearchResultTransfer
    {
        $paginationConfig = $this->getFactory()->getSearchConfig()->getPaginationConfig();

        $searchResultPagination = $searchResult->getPaginationOrFail();

        $paginationSearchResultTransfer = new PaginationSearchResultTransfer();
        $paginationSearchResultTransfer
            ->setNumFound($searchResultPagination->getNumFound())
            ->setCurrentPage($searchResultPagination->getCurrentPage())
            ->setMaxPage($this->calculateMaxPage($searchResultPagination))
            ->setCurrentItemsPerPage($searchResultPagination->getCurrentItemsPerPage())
            ->setConfig(clone $paginationConfig->getPagination());

        return $paginationSearchResultTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchHttpResponsePaginationTransfer $searchResultPagination
     *
     * @return int
     */
    protected function calculateMaxPage(SearchHttpResponsePaginationTransfer $searchResultPagination): int
    {
        return (int)round($searchResultPagination->getNumFoundOrFail() / $searchResultPagination->getCurrentItemsPerPage());
    }
}

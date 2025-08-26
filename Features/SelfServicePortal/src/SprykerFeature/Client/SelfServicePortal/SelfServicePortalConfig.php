<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SelfServicePortal;

use Generated\Shared\Transfer\PaginationConfigTransfer;
use Generated\Shared\Transfer\SortConfigTransfer;
use Spryker\Shared\Kernel\AbstractSharedConfig;

class SelfServicePortalConfig extends AbstractSharedConfig
{
    /**
     * @uses \Spryker\Shared\SearchElasticsearch\SearchElasticsearchConstants::FULL_TEXT_BOOSTED_BOOSTING_VALUE
     *
     * @var string
     */
    protected const FULL_TEXT_BOOSTED_BOOSTING_VALUE = 'SEARCH_ELASTICSEARCH:FULL_TEXT_BOOSTED_BOOSTING_VALUE';

    /**
     * Specification:
     * - Returns the boosting value for asset's full text boosted fields in Elasticsearch queries.
     * - Used to increase the relevance score of matches in boosted full text fields.
     * - Applied to Wildcard queries for enhanced pattern matching performance.
     * - Applied to MultiMatch queries to prioritize boosted field matches.
     *
     * @api
     *
     * @return int
     */
    public function getElasticsearchFullTextBoostedBoostingValue(): int
    {
        return $this->get(static::FULL_TEXT_BOOSTED_BOOSTING_VALUE, 3);
    }

    /**
     * @var string
     */
    protected const PAGINATION_PARAMETER_NAME_PAGE = 'page';

    /**
     * @var string
     */
    protected const PAGINATION_ITEMS_PER_PAGE_PARAMETER_NAME = 'ipp';

    /**
     * @var int
     */
    protected const PAGINATION_DEFAULT_ITEMS_PER_PAGE = 10;

    /**
     * @var int
     */
    protected const PAGINATION_MAX_ITEMS_PER_PAGE = 100;

    /**
     * @var string
     */
    protected const SORT_NAME = 'name';

    /**
     * @var string
     */
    protected const SORT_PARAMETER_NAME_ASC = 'name_asc';

    /**
     * @var string
     */
    protected const SORT_PARAMETER_NAME_DESC = 'name_desc';

    /**
     * Specification:
     * - Returns pagination configuration for SSP asset search.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\PaginationConfigTransfer
     */
    public function getSspAssetSearchPaginationConfigTransfer(): PaginationConfigTransfer
    {
        return (new PaginationConfigTransfer())
            ->setParameterName(static::PAGINATION_PARAMETER_NAME_PAGE)
            ->setItemsPerPageParameterName(static::PAGINATION_ITEMS_PER_PAGE_PARAMETER_NAME)
            ->setDefaultItemsPerPage(static::PAGINATION_DEFAULT_ITEMS_PER_PAGE)
            ->setMaxItemsPerPage(static::PAGINATION_MAX_ITEMS_PER_PAGE);
    }

    /**
     * Specification:
     * - Returns sort configuration for ascending name sorting.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\SortConfigTransfer
     */
    public function getAscendingNameSortConfigTransfer(): SortConfigTransfer
    {
        return (new SortConfigTransfer())
            ->setName(static::SORT_NAME)
            ->setParameterName(static::SORT_PARAMETER_NAME_ASC)
            ->setFieldName('string-sort')
            ->setIsDescending(false);
    }

    /**
     * Specification:
     * - Returns sort configuration for descending name sorting.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\SortConfigTransfer
     */
    public function getDescendingNameSortConfigTransfer(): SortConfigTransfer
    {
        return (new SortConfigTransfer())
            ->setName(static::SORT_NAME)
            ->setParameterName(static::SORT_PARAMETER_NAME_DESC)
            ->setFieldName('string-sort')
            ->setIsDescending(true);
    }

    /**
     * Specification:
     * - Returns default sort configuration for SSP asset search.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\SortConfigTransfer
     */
    public function getDefaultSortConfigTransfer(): SortConfigTransfer
    {
        return $this->getAscendingNameSortConfigTransfer();
    }
}

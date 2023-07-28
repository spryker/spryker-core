<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ServicePointSearch;

use Generated\Shared\Search\ServicePointIndexMap;
use Generated\Shared\Transfer\PaginationConfigTransfer;
use Generated\Shared\Transfer\SortConfigTransfer;
use Spryker\Client\Kernel\AbstractBundleConfig;

class ServicePointSearchConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const PAGINATION_DEFAULT_ITEMS_PER_PAGE = 10;

    /**
     * @var int
     */
    protected const PAGINATION_MAX_ITEMS_PER_PAGE = 10000;

    /**
     * @var string
     */
    protected const PAGINATION_PARAMETER_NAME_PAGE = 'page';

    /**
     * @var string
     */
    protected const PAGINATION_ITEMS_PER_PAGE_PARAMETER_NAME = 'ipp';

    /**
     * @var string
     */
    protected const SORT_CITY = 'city';

    /**
     * @var string
     */
    protected const SORT_PARAMETER_CITY_ASC = 'city_asc';

    /**
     * @var string
     */
    protected const SORT_PARAMETER_CITY_DESC = 'city_desc';

    /**
     * @uses \Spryker\Shared\SearchElasticsearch\SearchElasticsearchConstants::FULL_TEXT_BOOSTED_BOOSTING_VALUE
     *
     * @var string
     */
    protected const FULL_TEXT_BOOSTED_BOOSTING_VALUE = 'SEARCH_ELASTICSEARCH:FULL_TEXT_BOOSTED_BOOSTING_VALUE';

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\PaginationConfigTransfer
     */
    public function getServicePointSearchPaginationConfigTransfer(): PaginationConfigTransfer
    {
        return (new PaginationConfigTransfer())
            ->setParameterName(static::PAGINATION_PARAMETER_NAME_PAGE)
            ->setItemsPerPageParameterName(static::PAGINATION_ITEMS_PER_PAGE_PARAMETER_NAME)
            ->setDefaultItemsPerPage(static::PAGINATION_DEFAULT_ITEMS_PER_PAGE)
            ->setMaxItemsPerPage(static::PAGINATION_MAX_ITEMS_PER_PAGE);
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\SortConfigTransfer
     */
    public function getAscendingCitySortConfigTransfer(): SortConfigTransfer
    {
        return (new SortConfigTransfer())
            ->setName(static::SORT_CITY)
            ->setParameterName(static::SORT_PARAMETER_CITY_ASC)
            ->setFieldName(ServicePointIndexMap::STRING_SORT)
            ->setIsDescending(false);
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\SortConfigTransfer
     */
    public function getDescendingCitySortConfigTransfer(): SortConfigTransfer
    {
        return (new SortConfigTransfer())
            ->setName(static::SORT_CITY)
            ->setParameterName(static::SORT_PARAMETER_CITY_DESC)
            ->setFieldName(ServicePointIndexMap::STRING_SORT)
            ->setIsDescending(true);
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\SortConfigTransfer
     */
    public function getDefaultSortConfigTransfer(): SortConfigTransfer
    {
        return (new SortConfigTransfer())
            ->setName(static::SORT_CITY)
            ->setParameterName(static::SORT_PARAMETER_CITY_ASC)
            ->setFieldName(ServicePointIndexMap::STRING_SORT)
            ->setIsDescending(false);
    }

    /**
     * @api
     *
     * @return int
     */
    public function getElasticsearchFullTextBoostedBoostingValue(): int
    {
        return $this->get(static::FULL_TEXT_BOOSTED_BOOSTING_VALUE, 1);
    }
}

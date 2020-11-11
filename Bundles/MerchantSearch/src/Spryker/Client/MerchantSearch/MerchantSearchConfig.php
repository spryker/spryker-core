<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantSearch;

use Spryker\Client\Kernel\AbstractBundleConfig;

class MerchantSearchConfig extends AbstractBundleConfig
{
    protected const PAGINATION_DEFAULT_ITEMS_PER_PAGE = 10;
    protected const PAGINATION_MAX_ITEMS_PER_PAGE = 10000;
    protected const PAGINATION_PARAMETER_NAME_PAGE = 'page';
    protected const PAGINATION_ITEMS_PER_PAGE_PARAMETER_NAME = 'ipp';

    /**
     * Specification:
     * - Returns page parameter name for the search request.
     *
     * @api
     *
     * @return string
     */
    public function getPageParameterName(): string
    {
        return static::PAGINATION_PARAMETER_NAME_PAGE;
    }

    /**
     * Specification:
     * - Returns number, that is used for items per page request parameter validation.
     *
     * @api
     *
     * @return int
     */
    public function getMaxItemsPerPage(): int
    {
        return static::PAGINATION_MAX_ITEMS_PER_PAGE;
    }

    /**
     * Specification:
     * - Returns number, that will be used if items per page parameter not provided in the request.
     *
     * @api
     *
     * @return int
     */
    public function getDefaultItemsPerPage(): int
    {
        return static::PAGINATION_DEFAULT_ITEMS_PER_PAGE;
    }

    /**
     * Specification:
     * - Returns items per page parameter name for the search request.
     *
     * @api
     *
     * @return string
     */
    public function getItemsPerPageParameterName(): string
    {
        return static::PAGINATION_ITEMS_PER_PAGE_PARAMETER_NAME;
    }
}

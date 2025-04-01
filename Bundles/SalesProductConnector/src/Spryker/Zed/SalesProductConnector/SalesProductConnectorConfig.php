<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector;

use Generated\Shared\Transfer\ItemMetadataTransfer;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class SalesProductConnectorConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const POPULARITY_DAYS_INTERVAL = 90;

    /**
     * @var int
     */
    protected const PRODUCT_PAGE_DATA_REFRESH_DAYS_INTERVAL = 1;

    /**
     * @var list<string>
     */
    protected const EXCLUDED_METADATA_ATTRIBUTES = [
        ItemMetadataTransfer::SUPER_ATTRIBUTES,
    ];

    /**
     * Specification:
     * - Defines the interval in days which uses for calculate popularity.
     *
     * @api
     *
     * @return int
     */
    public function getPopularityDaysInterval(): int
    {
        return static::POPULARITY_DAYS_INTERVAL;
    }

    /**
     * Specification:
     * - Defines the interval in days which uses for retrieving productAbstractIds that are need refresh.
     *
     * @api
     *
     * @return int
     */
    public function getProductPageDataRefreshDaysInterval(): int
    {
        return static::PRODUCT_PAGE_DATA_REFRESH_DAYS_INTERVAL;
    }

    /**
     * Specification:
     * - Returns list of metadata attributes to be excluded during metadata saving.
     *
     * @api
     *
     * @return list<string>
     */
    public function getExcludedMetadataAttributes(): array
    {
        return static::EXCLUDED_METADATA_ATTRIBUTES;
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMerchantPortalGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class MerchantRelationshipMerchantPortalGuiConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const DEFAULT_MERCHANT_RELATIONSHIP_TABLE_PAGE_SIZE = 10;

    /**
     * @var int
     */
    protected const DEFAULT_MERCHANT_RELATIONSHIP_DASHBOARD_TABLE_ROW_LIMIT = 5;

    /**
     * @var int
     */
    protected const READ_MERCHANT_RELATIONSHIP_COLLECTION_BATCH_SIZE = 1000;

    /**
     * @var int
     */
    protected const MERCHANT_RELATIONSHIP_TABLE_BUSINESS_UNITS_COLUMN_LIMIT = 1;

    /**
     * Specification:
     * - Returns the default page size for the merchant relationship table.
     *
     * @api
     *
     * @return int
     */
    public function getDefaultMerchantRelationRequestTablePageSize(): int
    {
        return static::DEFAULT_MERCHANT_RELATIONSHIP_TABLE_PAGE_SIZE;
    }

    /**
     * Specification:
     *  - Returns the batch size for merchant relationship collection reading.
     *
     * @api
     *
     * @return int
     */
    public function getReadMerchantRelationshipCollectionBatchSize(): int
    {
        return static::READ_MERCHANT_RELATIONSHIP_COLLECTION_BATCH_SIZE;
    }

    /**
     * Specification:
     * - Returns the limit of chips for `businessUnits` merchant relationship table column.
     *
     * @api
     *
     * @return int
     */
    public function getMerchantRelationshipTableBusinessUnitsColumnLimit(): int
    {
        return static::MERCHANT_RELATIONSHIP_TABLE_BUSINESS_UNITS_COLUMN_LIMIT;
    }

    /**
     * Specification:
     * - Returns the limit of rows for merchant relationship table displayed on dashboard.
     *
     * @api
     *
     * @return int
     */
    public function getMerchantRelationshipDashboardTableRowLimit(): int
    {
        return static::DEFAULT_MERCHANT_RELATIONSHIP_DASHBOARD_TABLE_ROW_LIMIT;
    }
}

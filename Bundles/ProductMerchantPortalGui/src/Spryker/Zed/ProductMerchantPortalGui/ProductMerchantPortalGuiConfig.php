<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductMerchantPortalGuiConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Shared\ProductApproval\ProductApprovalConfig::STATUS_WAITING_FOR_APPROVAL
     *
     * @var string
     */
    protected const STATUS_WAITING_FOR_APPROVAL = 'waiting_for_approval';

    /**
     * @uses \Spryker\Shared\ProductApproval\ProductApprovalConfig::STATUS_APPROVED
     *
     * @var string
     */
    protected const STATUS_APPROVED = 'approved';

    /**
     * @uses \Spryker\Shared\ProductApproval\ProductApprovalConfig::STATUS_DENIED
     *
     * @var string
     */
    protected const STATUS_DENIED = 'denied';

    /**
     * @uses \Spryker\Shared\ProductApproval\ProductApprovalConfig::STATUS_DRAFT
     *
     * @var string
     */
    protected const STATUS_DRAFT = 'draft';

    /**
     * @var int
     */
    protected const MAIN_CATEGORY_ID = 1;

    /**
     * @var int
     */
    protected const DASHBOARD_EXPIRING_PRODUCTS_DAYS_THRESHOLD = 5;

    /**
     * @var int
     */
    protected const DASHBOARD_LOW_STOCK_THRESHOLD = 5;

    /**
     * Specification:
     * - Defines main category ID which is used as a starting point for category tree building.
     *
     * @api
     *
     * @return int
     */
    public function getMainCategoryIdForCategoryFilter(): int
    {
        return static::MAIN_CATEGORY_ID;
    }

    /**
     * Specification:
     * - Returns map of product approval statuses available for update.
     *
     * @api
     *
     * @return array<string, array<string>>
     */
    public function getProductApprovalUpdateStatusTree(): array
    {
        return [
            static::STATUS_WAITING_FOR_APPROVAL => [
                static::STATUS_DRAFT,
            ],
            static::STATUS_DRAFT => [
                static::STATUS_WAITING_FOR_APPROVAL,
            ],
            static::STATUS_DENIED => [
                static::STATUS_DRAFT,
            ],
        ];
    }

    /**
     * Specification:
     * - Returns the threshold in days for products to be considered as expiring on the dashboard.
     *
     * @api
     *
     * @return int
     */
    public function getDashboardExpiringProductsDaysThreshold(): int
    {
        return static::DASHBOARD_EXPIRING_PRODUCTS_DAYS_THRESHOLD;
    }

    /**
     * Specification:
     * - Returns the threshold for products to be considered as low stock on the dashboard.
     *
     * @api
     *
     * @return int
     */
    public function getDashboardLowStockThreshold(): int
    {
        return static::DASHBOARD_LOW_STOCK_THRESHOLD;
    }
}

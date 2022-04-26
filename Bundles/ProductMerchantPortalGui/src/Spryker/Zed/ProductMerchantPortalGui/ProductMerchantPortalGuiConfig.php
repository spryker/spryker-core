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
     * - Defines main category ID which is used as a starting point for category tree building for form options.
     *
     * @api
     *
     * @return int
     */
    public function getMainCategoryIdForCategoryOptions(): int
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
}

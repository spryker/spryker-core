<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApprovalGui;

use Spryker\Shared\ProductApproval\ProductApprovalConfig as SharedProductApprovalConfig;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductApprovalGuiConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Determines whether to use {@link \Spryker\Zed\ProductApprovalGui\ProductApprovalGuiConfig::getProductApprovalTableActionStatusTree()} for rendering product approval action buttons.
     *
     * @api
     *
     * @return bool
     */
    public function isApprovalStatusTreeCustomizationEnabled(): bool
    {
        return false;
    }

    /**
     * Specification:
     * - Returns available product approval statuses transition map.
     *
     * @api
     *
     * @return array<string, array<string>>
     */
    public function getProductApprovalTableActionStatusTree(): array
    {
        return [
            SharedProductApprovalConfig::STATUS_WAITING_FOR_APPROVAL => [
                SharedProductApprovalConfig::STATUS_APPROVED,
                SharedProductApprovalConfig::STATUS_DENIED,
            ],
            SharedProductApprovalConfig::STATUS_APPROVED => [
                SharedProductApprovalConfig::STATUS_DENIED,
            ],
            SharedProductApprovalConfig::STATUS_DENIED => [
                SharedProductApprovalConfig::STATUS_APPROVED,
            ],
            SharedProductApprovalConfig::STATUS_DRAFT => [
                SharedProductApprovalConfig::STATUS_APPROVED,
                SharedProductApprovalConfig::STATUS_DENIED,
            ],
        ];
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApproval;

use Spryker\Shared\ProductApproval\ProductApprovalConfig as SharedProductApprovalConfig;
use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\ProductApproval\ProductApprovalConfig getSharedConfig()
 */
class ProductApprovalConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Returns default approval status for products.
     *
     * @api
     *
     * @return string
     */
    public function getDefaultProductApprovalStatus(): string
    {
        return SharedProductApprovalConfig::STATUS_DRAFT;
    }

    /**
     * Specification:
     * - Returns mapped available product approval statuses array with status as key.
     *
     * @api
     *
     * @return array<string, array<string>>
     */
    public function getStatusTree(): array
    {
        return [
            SharedProductApprovalConfig::STATUS_WAITING_FOR_APPROVAL => [
                SharedProductApprovalConfig::STATUS_APPROVED,
                SharedProductApprovalConfig::STATUS_DENIED,
                SharedProductApprovalConfig::STATUS_DRAFT,
            ],
            SharedProductApprovalConfig::STATUS_APPROVED => [
                SharedProductApprovalConfig::STATUS_DENIED,
                SharedProductApprovalConfig::STATUS_DRAFT,
            ],
            SharedProductApprovalConfig::STATUS_DENIED => [
                SharedProductApprovalConfig::STATUS_APPROVED,
                SharedProductApprovalConfig::STATUS_DRAFT,
            ],
            SharedProductApprovalConfig::STATUS_DRAFT => [
                SharedProductApprovalConfig::STATUS_APPROVED,
                SharedProductApprovalConfig::STATUS_WAITING_FOR_APPROVAL,
                SharedProductApprovalConfig::STATUS_DENIED,
            ],
        ];
    }
}

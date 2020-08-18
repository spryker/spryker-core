<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer;

use Spryker\Shared\ProductOffer\ProductOfferConfig as SharedProductOfferConfig;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductOfferConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Returns default status for product offer.
     *
     * @api
     *
     * @return string
     */
    public function getDefaultApprovalStatus(): string
    {
        return SharedProductOfferConfig::STATUS_APPROVED;
    }

    /**
     * Specification:
     * - Returns mapped available statuses array with status as key.
     *
     * @api
     *
     * @return array
     */
    public function getStatusTree(): array
    {
        return [
            SharedProductOfferConfig::STATUS_WAITING_FOR_APPROVAL => [
                SharedProductOfferConfig::STATUS_APPROVED,
                SharedProductOfferConfig::STATUS_DENIED,
            ],
            SharedProductOfferConfig::STATUS_APPROVED => [
                SharedProductOfferConfig::STATUS_DENIED,
            ],
            SharedProductOfferConfig::STATUS_DENIED => [
                SharedProductOfferConfig::STATUS_APPROVED,
            ],
        ];
    }
}

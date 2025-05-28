<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductOfferGui;

/**
 * Represents approval statuses for product offers
 */
enum ProductOfferApprovalStatusEnum: string
{
    case WAITING_FOR_APPROVAL = ProductOfferGuiConfig::STATUS_WAITING_FOR_APPROVAL;
    case APPROVED = ProductOfferGuiConfig::STATUS_APPROVED;
    case DENIED = ProductOfferGuiConfig::STATUS_DENIED;

    /**
     * @return array<string, string>
     */
    public static function getOptionsArray(): array
    {
        return [
            static::WAITING_FOR_APPROVAL->value => 'Waiting for Approval',
            static::APPROVED->value => 'Approved',
            static::DENIED->value => 'Denied',
        ];
    }
}

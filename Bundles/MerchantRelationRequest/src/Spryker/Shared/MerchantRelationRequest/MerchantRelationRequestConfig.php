<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\MerchantRelationRequest;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class MerchantRelationRequestConfig extends AbstractSharedConfig
{
    /**
     * @api
     *
     * @var string
     */
    public const STATUS_PENDING = 'pending';

    /**
     * @api
     *
     * @var string
     */
    public const STATUS_REJECTED = 'rejected';

    /**
     * @api
     *
     * @var string
     */
    public const STATUS_APPROVED = 'approved';

    /**
     * @api
     *
     * @var string
     */
    public const STATUS_CANCELED = 'canceled';

    /**
     * Specification:
     * - Returns a list of request statuses that can be canceled.
     *
     * @api
     *
     * @return list<string>
     */
    public function getCancelableRequestStatuses(): array
    {
        return [
            static::STATUS_PENDING,
        ];
    }

    /**
     * Specification:
     * - Returns a list of request statuses that can be rejected.
     *
     * @api
     *
     * @return list<string>
     */
    public function getRejectableRequestStatuses(): array
    {
        return [
            static::STATUS_PENDING,
        ];
    }

    /**
     * Specification:
     * - Returns a list of request statuses that can be approved.
     *
     * @api
     *
     * @return list<string>
     */
    public function getApprovableRequestStatuses(): array
    {
        return [
            static::STATUS_PENDING,
        ];
    }

    /**
     * Specification:
     * - Returns a list of request statuses that can be updated to pending status.
     *
     * @api
     *
     * @return list<string>
     */
    public function getPendingUpdateRequestStatuses(): array
    {
        return [
            static::STATUS_PENDING,
        ];
    }

    /**
     * Specification:
     * - Returns list of merchant relation request statuses which trigger status update notification email sending.
     *
     * @api
     *
     * @return list<string>
     */
    public function getApplicableForRequestStatusChangeMailNotificationStatuses(): array
    {
        return [
            static::STATUS_APPROVED,
            static::STATUS_REJECTED,
        ];
    }
}

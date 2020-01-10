<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\MerchantOpeningHoursStorage;

class MerchantOpeningHoursStorageConfig
{
    /**
     * Specification:
     * - Queue name as used for processing merchant opening hours messages.
     *
     * @api
     */
    public const MERCHANT_OPENING_HOURS_SYNC_STORAGE_QUEUE = 'sync.storage.merchant_opening_hours';

    /**
     * Specification:
     * - Queue name as used for processing merchant opening hours messages.
     *
     * @api
     */
    public const MERCHANT_OPENING_HOURS_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.merchant_opening_hours.error';

    /**
     * Specification:
     * - Key generation resource name of merchant opening hours.
     *
     * @api
     */
    public const MERCHANT_OPENING_HOURS_RESOURCE_NAME = 'merchant_opening_hours';
}

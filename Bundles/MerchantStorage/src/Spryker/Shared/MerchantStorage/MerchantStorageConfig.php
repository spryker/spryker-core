<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\MerchantStorage;

class MerchantStorageConfig
{
    /**
     * Specification:
     * - Queue name as used for processing merchant messages.
     *
     * @api
     */
    public const MERCHANT_SYNC_STORAGE_QUEUE = 'sync.storage.merchant';

    /**
     * Specification:
     * - Queue name as used for error merchant messages.
     *
     * @api
     */
    public const MERCHANT_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.merchant.error';

    /**
     * Specification:
     * - Resource name, this will use for key generating.
     *
     * @api
     */
    public const MERCHANT_RESOURCE_NAME = 'merchant';
}

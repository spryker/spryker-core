<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\MerchantProfileStorage;

class MerchantProfileStorageConfig
{
    /**
     * Specification:
     * - Queue name as used for processing merchant messages.
     *
     * @api
     */
    public const MERCHANT_PROFILE_SYNC_STORAGE_QUEUE = 'sync.storage.merchant_profile';
    /**
     * Specification:
     * - Queue name as used for error merchant messages.
     *
     * @api
     */
    public const MERCHANT_PROFILE_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.merchant_profile.error';
    /**
     * Specification:
     * - Resource name, this will use for key generating.
     *
     * @api
     */
    public const MERCHANT_PROFILE_RESOURCE_NAME = 'merchant_profile';
}

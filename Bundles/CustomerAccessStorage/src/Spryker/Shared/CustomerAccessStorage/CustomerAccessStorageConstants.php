<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CustomerAccessStorage;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface CustomerAccessStorageConstants
{
    /**
     * Specification:
     * - Queue name as used for processing customer access messages
     *
     * @api
     */
    public const CUSTOMER_ACCESS_SYNC_STORAGE_QUEUE = 'sync.storage.customer_access';

    /**
     * Specification:
     * - Queue name as used for processing customer access error messages
     *
     * @api
     */
    public const CUSTOMER_ACCESS_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.customer_access.error';

    /**
     * Specification:
     * - Resource name, this will use for key generating
     *
     * @api
     */
    public const CUSTOMER_ACCESS_RESOURCE_NAME = 'unauthenticated_customer_access';
}

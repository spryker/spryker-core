<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CustomerAccessStorage;

class CustomerAccessStorageConstants
{
    /**
     * Specification:
     * - Queue name as used for processing customer access messages
     *
     * @api
     */
    const CUSTOMER_ACCESS_SYNC_STORAGE_QUEUE = 'sync.storage.access';

    /**
     * Specification:
     * - Queue name as used for processing customer access error messages
     *
     * @api
     */
    const CUSTOMER_ACCESS_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.access.error';

    /**
     * Specification:
     * - Resource name, this will use for key generating
     *
     * @api
     */
    const CUSTOMER_ACCESS_RESOURCE_NAME = 'unauthenticated_customer_access';
}

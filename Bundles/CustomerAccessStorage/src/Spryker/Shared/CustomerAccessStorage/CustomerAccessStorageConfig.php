<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CustomerAccessStorage;

class CustomerAccessStorageConfig
{
    /**
     * Specification:
     * - This event will be used for `spy_unauthenticated_customer_access` publishing.
     *
     * @api
     *
     * @var string
     */
    public const UNAUTHENTICATED_CUSTOMER_ACCESS_PUBLISH = 'CustomerAccess.unauthenticated_customer_access.publish';
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CustomerStorage;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class CustomerStorageConfig extends AbstractSharedConfig
{
    /**
     * Specification:
     * - Defines queue name as used for processing customer invalidated.
     *
     * @api
     *
     * @var string
     */
    public const PUBLISH_CUSTOMER_INVALIDATED = 'publish.customer_invalidated';

    /**
     * Specification:
     * - This events will be used for `spy_customer` entity changes.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_CUSTOMER_UPDATE = 'Entity.spy_customer.update';

    /**
     * Specification:
     * - Resource name, this will be used for key generating.
     *
     * @api
     *
     * @var string
     */
    public const CUSTOMER_RESOURCE_NAME = 'customer_invalidated';

    /**
     * Specification:
     * - Queue name as used for processing customer invalidated messages.
     *
     * @api
     *
     * @var string
     */
    public const CUSTOMER_INVALIDATED_SYNC_STORAGE_QUEUE = 'sync.storage.customer_invalidated';
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\TaxStorage;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class TaxStorageConfig extends AbstractSharedConfig
{
    /**
     * Specification:
     *  - Queue name as used for processing tax set messages.
     *
     * @api
     */
    public const TAX_SET_SYNC_STORAGE_QUEUE = 'sync.storage.tax_set';

    /**
     * Specification:
     *  - Queue name as used for processing tax set error messages.
     *
     * @api
     */
    public const TAX_SET_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.tax_set.error';

    /**
     * Specification:
     *  - Resource name, this will use for key generation.
     *
     * @api
     */
    public const TAX_SET_RESOURCE_NAME = 'tax_set';
}

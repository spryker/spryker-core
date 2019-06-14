<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\TaxProductStorage;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class TaxProductStorageConfig extends AbstractSharedConfig
{
    /**
     * Specification:
     *  - Queue name as used for processing product abstract product tax set messages.
     *
     * @api
     */
    public const PRODUCT_ABSTRACT_TAX_SET_SYNC_STORAGE_QUEUE = 'sync.storage.product_abstract_tax_set';

    /**
     * Specification:
     *  - Queue name as used for processing product abstract tax set error messages.
     *
     * @api
     */
    public const PRODUCT_ABSTRACT_TAX_SET_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.product_abstract_tax_set.error';

    /**
     * Specification:
     *  - Resource name, this will use for key generation.
     *
     * @api
     */
    public const PRODUCT_ABSTRACT_TAX_SET_RESOURCE_NAME = 'product_abstract_tax_set';
}

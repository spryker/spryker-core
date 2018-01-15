<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\PriceProductStorage;

class PriceProductStorageConstants
{
    /**
     * Specification:
     * - Queue name as used for processing price messages
     *
     * @api
     */
    const PRICE_SYNC_STORAGE_QUEUE = 'sync.storage.price';

    /**
     * Specification:
     * - Queue name as used for processing price messages
     *
     * @api
     */
    const PRICE_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.price.error';

    /**
     * Specification:
     * - Resource name, this will use for key generating
     *
     * @api
     */
    const PRICE_ABSTRACT_RESOURCE_NAME = 'price_product_abstract';

    /**
     * Specification:
     * - Resource name, this will use for key generating
     *
     * @api
     */
    const PRICE_CONCRETE_RESOURCE_NAME = 'price_product_concrete';
}

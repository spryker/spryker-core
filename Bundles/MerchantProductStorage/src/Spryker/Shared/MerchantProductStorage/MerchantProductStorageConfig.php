<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\MerchantProductStorage;

class MerchantProductStorageConfig
{
    /**
     * Specification:
     * - ID product abstract attribute as used for selected attributes.
     *
     * @api
     */
    public const FK_PRODUCT_ABSTRACT_ATTRIBUTE = 'fk_product_abstract';

    /**
     * Specification:
     * - Queue name as used for processing merchant product abstract messages.
     *
     * @api
     */
    public const MERCHANT_PRODUCT_ABSTRACT_SYNC_STORAGE_QUEUE = 'sync.storage.merchant_product_abstract';

    /**
     * Specification:
     * - Queue name as used for processing merchant product abstract messages.
     *
     * @api
     */
    public const MERCHANT_PRODUCT_ABSTRACT_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.merchant_product_abstract.error';

    /**
     * Specification:
     * - Resource name, this will use for key generating.
     *
     * @api
     */
    public const RESOURCE_MERCHANT_PRODUCT_ABSTRACT_NAME = 'merchant_product_abstract';
}

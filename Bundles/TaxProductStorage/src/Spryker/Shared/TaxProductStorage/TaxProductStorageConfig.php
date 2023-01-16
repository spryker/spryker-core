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
     * - Queue name as used for processing product abstract product tax set messages.
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_ABSTRACT_TAX_SET_SYNC_STORAGE_QUEUE = 'sync.storage.product_abstract_tax_set';

    /**
     * Specification:
     * - Queue name as used for processing product abstract tax set error messages.
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_ABSTRACT_TAX_SET_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.product_abstract_tax_set.error';

    /**
     * Specification:
     *  - Resource name, this will use for key generation.
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_ABSTRACT_TAX_SET_RESOURCE_NAME = 'product_abstract_tax_set';

    /**
     * Specification:
     * - Tax product resource name, used for key generation.
     *
     * @api
     *
     * @var string
     */
    public const TAX_PRODUCT_RESOURCE_NAME = 'tax_product';

    /**
     * Specification
     * - This event will be used for tax product publishing.
     *
     * @api
     *
     * @var string
     */
    public const TAX_PRODUCT_PUBLISH = 'TaxProduct.tax_product.publish';
}

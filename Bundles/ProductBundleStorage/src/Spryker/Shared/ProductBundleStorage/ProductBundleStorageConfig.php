<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductBundleStorage;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class ProductBundleStorageConfig extends AbstractSharedConfig
{
    /**
     * Specification:
     * - Queue name as used for processing product_bundle messages.
     *
     * @api
     */
    public const PRODUCT_BUNDLE_SYNC_STORAGE_QUEUE = 'sync.storage.product_bundle';

    /**
     * Specification:
     * - Resource name, this will be used for key generation.
     *
     * @api
     */
    public const PRODUCT_BUNDLE_RESOURCE_NAME = 'product_bundle';

    /**
     * Specification:
     * - This events will be used for spy_product_bundle publishing.
     *
     * @api
     */
    public const PRODUCT_BUNDLE_PUBLISH = 'ProductBundle.product_bundle.publish';

    /**
     * Specification:
     * - This event is used for spy_product_bundle entity creation.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_BUNDLE_CREATE = 'Entity.spy_product_bundle.create';

    /**
     * Specification:
     * - This events will be used for spy_product_bundle entity changes.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_BUNDLE_UPDATE = 'Entity.spy_product_bundle.update';

    /**
     * Specification:
     * - This event is used for spy_product_bundle entity deletion.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_BUNDLE_DELETE = 'Entity.spy_product_bundle.delete';

    /**
     * Specification:
     * - This event is used for spy_product entity changes.
     *
     * @api
     *
     * @uses \Spryker\Shared\ProductStorage\ProductStorageConfig::ENTITY_SPY_PRODUCT_UPDATE
     */
    public const ENTITY_SPY_PRODUCT_UPDATE = 'Entity.spy_product.update';
}

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
     *
     * @uses \Spryker\Shared\ProductStorage\ProductStorageConstants::PRODUCT_SYNC_STORAGE_QUEUE
     *
     * @var string
     */
    public const PRODUCT_SYNC_STORAGE_QUEUE = 'sync.storage.product';

    /**
     * Specification:
     * - Resource name, this will be used for key generation.
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_BUNDLE_RESOURCE_NAME = 'product_bundle';

    /**
     * Specification:
     * - This events will be used for spy_product_bundle publishing.
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_BUNDLE_PUBLISH = 'ProductBundle.product_bundle.publish.write';

    /**
     * Specification:
     * - This event is used for spy_product_bundle entity creation.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_BUNDLE_CREATE = 'Entity.spy_product_bundle.create';

    /**
     * Specification:
     * - This events will be used for spy_product_bundle entity changes.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_BUNDLE_UPDATE = 'Entity.spy_product_bundle.update';

    /**
     * Specification:
     * - This event is used for spy_product_bundle entity deletion.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_BUNDLE_DELETE = 'Entity.spy_product_bundle.delete';

    /**
     * Specification:
     * - This event is used for spy_product entity changes.
     *
     * @api
     *
     * @uses \Spryker\Zed\Product\Dependency\ProductEvents::ENTITY_SPY_PRODUCT_UPDATE
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_UPDATE = 'Entity.spy_product.update';
}

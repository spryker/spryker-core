<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductCategoryStorage;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class ProductCategoryStorageConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Queue name as used for processing price messages
     *
     * @api
     */
    public const PRODUCT_ABSTRACT_CATEGORY_SYNC_STORAGE_QUEUE = 'sync.storage.product';

    /**
     * Specification:
     * - Queue name as used for processing price messages
     *
     * @api
     */
    public const PRODUCT_ABSTRACT_CATEGORY_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.product.error';

    /**
     * Specification:
     * - Resource name, this will use for key generating
     *
     * @api
     */
    public const PRODUCT_ABSTRACT_CATEGORY_RESOURCE_NAME = 'product_abstract_category';

    /**
     * Specification
     * - This events will be used for category_store publishing.
     *
     * @api
     */
    public const CATEGORY_STORE_PUBLISH = 'Category.category_store.publish';

    /**
     * Specification
     * - This events will be used for category_store un-publishing.
     *
     * @api
     */
    public const CATEGORY_STORE_UNPUBLISH = 'Category.category_store.unpublish';

    /**
     * Specification:
     * - This events will be used for spy_category_store entity creation.
     *
     * @api
     */
    public const ENTITY_SPY_CATEGORY_STORE_CREATE = 'Entity.spy_category_store.create';

    /**
     * Specification:
     * - This events will be used for spy_category_store entity changes.
     *
     * @api
     */
    public const ENTITY_SPY_CATEGORY_STORE_UPDATE = 'Entity.spy_category_store.update';

    /**
     * Specification:
     * - This events will be used for spy_category_store entity deletion.
     *
     * @api
     */
    public const ENTITY_SPY_CATEGORY_STORE_DELETE = 'Entity.spy_category_store.delete';
}

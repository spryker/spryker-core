<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductRelationStorage;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class ProductRelationStorageConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - This event is used for writing product relation to the storage.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_RELATION_CREATE = 'Entity.spy_product_relation.create';

    /**
     * Specification:
     * - This event is used for writing product relation to the storage.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_RELATION_UPDATE = 'Entity.spy_product_relation.update';

    /**
     * Specification:
     * - This event is used for writing product relation to the storage.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_RELATION_DELETE = 'Entity.spy_product_relation.delete';

    /**
     * Specification:
     * - This event is used for writing product relation to the storage.
     *
     * @api
     */
    public const PRODUCT_ABSTRACT_RELATION_PUBLISH = 'ProductRelation.product_abstract_relation.publish';

    /**
     * Specification:
     * - This event is used for writing product relation to the storage.
     *
     * @api
     */
    public const PRODUCT_ABSTRACT_RELATION_STORE_PUBLISH = 'ProductRelation.product_abstract_relation_store.publish';

    /**
     * Specification:
     * - This event is used for writing product relation to the storage.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_RELATION_PRODUCT_ABSTRACT_CREATE = 'Entity.spy_product_relation_product_abstract.create';

    /**
     * Specification:
     * - This event is used for writing product relation to the storage.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_RELATION_PRODUCT_ABSTRACT_DELETE = 'Entity.spy_product_relation_product_abstract.delete';

    /**
     * Specification:
     * - This event is used for writing product relation to the storage.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_RELATION_STORE_CREATE = 'Entity.spy_product_relation_store.create';

    /**
     * Specification:
     * - This event is used for writing product relation to the storage.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_RELATION_STORE_DELETE = 'Entity.spy_product_relation_store.delete';

    /**
     * Specification:
     * - Queue name as used for processing price messages
     *
     * @api
     */
    public const PRODUCT_ABSTRACT_RELATION_SYNC_STORAGE_QUEUE = 'sync.storage.product';

    /**
     * Specification:
     * - Queue name as used for processing price messages
     *
     * @api
     */
    public const PRODUCT_ABSTRACT_RELATION_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.product.error';

    /**
     * Specification:
     * - Resource name, this will use for key generating
     *
     * @api
     */
    public const PRODUCT_ABSTRACT_RELATION_RESOURCE_NAME = 'product_abstract_relation';
}

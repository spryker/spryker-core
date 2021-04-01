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

    /**
     * Specification:
     * - This events will be used for `spy_category_attribute` entity creation.
     *
     * @api
     */
    public const ENTITY_SPY_CATEGORY_ATTRIBUTE_CREATE = 'Entity.spy_category_attribute.create';

    /**
     * Specification:
     * - This events will be used for `spy_category_attribute` entity changes.
     *
     * @api
     */
    public const ENTITY_SPY_CATEGORY_ATTRIBUTE_UPDATE = 'Entity.spy_category_attribute.update';

    /**
     * Specification:
     * - This events will be used for `spy_category_attribute` entity deletion.
     *
     * @api
     */
    public const ENTITY_SPY_CATEGORY_ATTRIBUTE_DELETE = 'Entity.spy_category_attribute.delete';

    /**
     * Specification:
     * - This events will be used for `spy_category_node` entity creation.
     *
     * @api
     */
    public const ENTITY_SPY_CATEGORY_NODE_CREATE = 'Entity.spy_category_node.create';

    /**
     * Specification:
     * - This events will be used for `spy_category_node` entity changes.
     *
     * @api
     */
    public const ENTITY_SPY_CATEGORY_NODE_UPDATE = 'Entity.spy_category_node.update';

    /**
     * Specification:
     * - This events will be used for `spy_category_node` entity deletion.
     *
     * @api
     */
    public const ENTITY_SPY_CATEGORY_NODE_DELETE = 'Entity.spy_category_node.delete';

    /**
     * Specification:
     * - This events will be used for `spy_category` entity creation.
     *
     * @api
     */
    public const ENTITY_SPY_CATEGORY_CREATE = 'Entity.spy_category.create';

    /**
     * Specification:
     * - This events will be used for `spy_category` entity changes.
     *
     * @api
     */
    public const ENTITY_SPY_CATEGORY_UPDATE = 'Entity.spy_category.update';

    /**
     * Specification:
     * - This events will be used for `spy_category` entity deletion.
     *
     * @api
     */
    public const ENTITY_SPY_CATEGORY_DELETE = 'Entity.spy_category.delete';

    /**
     * Specification
     * - This events will be used for `spy_url` entity creation.
     *
     * @api
     */
    public const ENTITY_SPY_URL_CREATE = 'Entity.spy_url.create';

    /**
     * Specification
     * - This events will be used for `spy_url` entity changes.
     *
     * @api
     */
    public const ENTITY_SPY_URL_UPDATE = 'Entity.spy_url.update';

    /**
     * Specification
     * - This events will be used for `spy_url` entity deletion.
     *
     * @api
     */
    public const ENTITY_SPY_URL_DELETE = 'Entity.spy_url.delete';

    /**
     * Specification
     * - This events will be used for `product_category` publishing.
     *
     * @api
     */
    public const PRODUCT_CATEGORY_PUBLISH = 'ProductCategory.category.publish';

    /**
     * Specification
     * - This events will be used for `product_category` un-publishing.
     *
     * @api
     */
    public const PRODUCT_CATEGORY_UNPUBLISH = 'ProductCategory.category.unpublish';

    /**
     * Specification
     * - This events will be used for `spy_product_category` entity creation.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_CATEGORY_CREATE = 'Entity.spy_product_category.create';

    /**
     * Specification
     * - This events will be used for `spy_product_category` entity changes.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_CATEGORY_UPDATE = 'Entity.spy_product_category.update';

    /**
     * Specification
     * - This events will be used for `spy_product_category` entity deletion.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_CATEGORY_DELETE = 'Entity.spy_product_category.delete';
}

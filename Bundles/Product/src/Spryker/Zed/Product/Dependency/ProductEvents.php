<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Dependency;

interface ProductEvents
{
    /**
     * @var string
     */
    public const PRODUCT_ABSTRACT_BEFORE_CREATE = 'Product.product_abstract.before.create';

    /**
     * @var string
     */
    public const PRODUCT_ABSTRACT_BEFORE_UPDATE = 'Product.product_abstract.before.update';

    /**
     * @var string
     */
    public const PRODUCT_CONCRETE_BEFORE_CREATE = 'Product.product_concrete.before.create';

    /**
     * @var string
     */
    public const PRODUCT_CONCRETE_BEFORE_UPDATE = 'Product.product_concrete.before.update';

    /**
     * @var string
     */
    public const PRODUCT_ABSTRACT_AFTER_UPDATE = 'Product.product_abstract.after.update';

    /**
     * @var string
     */
    public const PRODUCT_ABSTRACT_AFTER_CREATE = 'Product.product_abstract.after.create';

    /**
     * @var string
     */
    public const PRODUCT_CONCRETE_AFTER_CREATE = 'Product.product_concrete.after.create';

    /**
     * @var string
     */
    public const PRODUCT_CONCRETE_AFTER_UPDATE = 'Product.product_concrete.after.update';

    /**
     * @var string
     */
    public const PRODUCT_ABSTRACT_READ = 'Product.product_abstract.read';

    /**
     * @var string
     */
    public const PRODUCT_CONCRETE_READ = 'Product.product_concrete.read';

    /**
     * @var string
     */
    public const PRODUCT_CONCRETE_EXPORT = 'Product.product_concrete.export';

    /**
     * @var string
     */
    public const PRODUCT_CONCRETE_UPDATE = 'Product.product_concrete.update';

    /**
     * Specification
     * - This event will be used for product abstract publishing
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_ABSTRACT_PUBLISH = 'Product.product_abstract.publish';

    /**
     * Specification
     * - This event triggers publishing of product abstracts solely for search purposes.
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_ABSTRACT_SEARCH_PUBLISH = 'Product.product_abstract_search.publish';

    /**
     * Specification
     * - This event will be used for product abstract updates.
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_ABSTRACT_UPDATE = 'Product.product_abstract.update';

    /**
     * Specification
     * - This events will be used for product_abstract un-publishing
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_ABSTRACT_UNPUBLISH = 'Product.product_abstract.unpublish';

    /**
     * Specification
     * - This events will be used for product_abstract publishing
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_CONCRETE_PUBLISH = 'Product.product_concrete.publish';

    /**
     * Specification
     * - This events will be used for product_abstract un-publishing
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_CONCRETE_UNPUBLISH = 'Product.product_concrete.unpublish';

    /**
     * Specification:
     * - Represents spy_product entity creation.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_CREATE = 'Entity.spy_product.create';

    /**
     * Specification:
     * - Represents spy_product entity changes.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_UPDATE = 'Entity.spy_product.update';

    /**
     * Specification:
     * - Represents spy_product entity deletion.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_DELETE = 'Entity.spy_product.delete';

    /**
     * Specification:
     * - Represents spy_product_localized_attributes entity creation.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_LOCALIZED_ATTRIBUTES_CREATE = 'Entity.spy_product_localized_attributes.create';

    /**
     * Specification:
     * - Represents spy_product_localized_attributes entity changes.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_LOCALIZED_ATTRIBUTES_UPDATE = 'Entity.spy_product_localized_attributes.update';

    /**
     * Specification:
     * - Represents spy_product_localized_attributes entity deletion.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_LOCALIZED_ATTRIBUTES_DELETE = 'Entity.spy_product_localized_attributes.delete';

    /**
     * Specification:
     * - Represents spy_product_abstract entity creation.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_ABSTRACT_CREATE = 'Entity.spy_product_abstract.create';

    /**
     * Specification:
     * - Represents spy_product_abstract entity changes.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_ABSTRACT_UPDATE = 'Entity.spy_product_abstract.update';

    /**
     * Specification:
     * - Represents spy_product_abstract entity deletion.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_ABSTRACT_DELETE = 'Entity.spy_product_abstract.delete';

    /**
     * Specification:
     * - Represents spy_product_abstract_localized_attributes entity creation.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_ABSTRACT_LOCALIZED_ATTRIBUTES_CREATE = 'Entity.spy_product_abstract_localized_attributes.create';

    /**
     * Specification:
     * - Represents spy_product_abstract_localized_attributes entity changes.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_ABSTRACT_LOCALIZED_ATTRIBUTES_UPDATE = 'Entity.spy_product_abstract_localized_attributes.update';

    /**
     * Specification:
     * - Represents spy_product_abstract_localized_attributes entity deletion.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_ABSTRACT_LOCALIZED_ATTRIBUTES_DELETE = 'Entity.spy_product_abstract_localized_attributes.delete';

    /**
     * Specification:
     * - Represents spy_product_attribute_key entity creation.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_ATTRIBUTE_KEY_CREATE = 'Entity.spy_product_attribute_key.create';

    /**
     * Specification:
     * - Represents spy_product_attribute_key entity changes.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_ATTRIBUTE_KEY_UPDATE = 'Entity.spy_product_attribute_key.update';

    /**
     * Specification:
     * - Represents spy_product_attribute_key entity deletion.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_ATTRIBUTE_KEY_DELETE = 'Entity.spy_product_attribute_key.delete';

    /**
     * Specification:
     * - Represents spy_product_abstract_store entity creation.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_ABSTRACT_STORE_CREATE = 'Entity.spy_product_abstract_store.create';

    /**
     * Specification:
     * - Represents spy_product_abstract_store entity changes.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_ABSTRACT_STORE_UPDATE = 'Entity.spy_product_abstract_store.update';

    /**
     * Specification:
     * - Represents spy_product_abstract_store entity deletion.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_ABSTRACT_STORE_DELETE = 'Entity.spy_product_abstract_store.delete';

    /**
     * @uses \Spryker\Zed\Url\Dependency\UrlEvents::ENTITY_SPY_URL_CREATE
     *
     * @var string
     */
    public const ENTITY_SPY_URL_CREATE = 'Entity.spy_url.create';

    /**
     * @uses \Spryker\Zed\Url\Dependency\UrlEvents::ENTITY_SPY_URL_UPDATE
     *
     * @var string
     */
    public const ENTITY_SPY_URL_UPDATE = 'Entity.spy_url.update';
}

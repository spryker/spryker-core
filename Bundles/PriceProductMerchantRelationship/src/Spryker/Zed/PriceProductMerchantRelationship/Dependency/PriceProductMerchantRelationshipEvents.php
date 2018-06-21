<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationship\Dependency;

interface PriceProductMerchantRelationshipEvents
{
    /**
     * Specification
     * - This event will be used for spy_price_product_business_unit entity creation
     *
     * @api
     */
    public const ENTITY_SPY_PRICE_PRODUCT_MERCHANT_RELATION_CREATE = 'Entity.spy_price_product_business_unit.create';

    /**
     * Specification
     * - This event will be used for spy_price_product_business_unit entity update
     *
     * @api
     */
    public const ENTITY_SPY_PRICE_PRODUCT_MERCHANT_RELATION_UPDATE = 'Entity.spy_price_product_business_unit.update';

    /**
     * Specification
     * - This event will be used for spy_price_product_business_unit entity delete
     *
     * @api
     */
    public const ENTITY_SPY_PRICE_PRODUCT_MERCHANT_RELATION_DELETE = 'Entity.spy_price_product_business_unit.delete';

    /**
     * Specification
     * - This event will be used for price_abstract publishing
     *
     * @api
     */
    public const PRICE_ABSTRACT_PUBLISH = 'Price.price_abstract.publish';

    /**
     * Specification
     * - This event will be used for price_abstract un-publishing
     *
     * @api
     */
    public const PRICE_ABSTRACT_UNPUBLISH = 'Price.price_abstract.unpublish';

    /**
     * Specification
     * - This event will be used for price_concrete publishing
     *
     * @api
     */
    public const PRICE_CONCRETE_PUBLISH = 'Price.price_concrete.publish';

    /**
     * Specification
     * - This event will be used for price_concrete un-publishing
     *
     * @api
     */
    public const PRICE_CONCRETE_UNPUBLISH = 'Price.price_concrete.unpublish';

    /**
     * Specification
     * - This event will be used for spy_price_product entity creation
     *
     * @api
     */
    public const ENTITY_SPY_PRICE_PRODUCT_CREATE = 'Entity.spy_price_product.create';

    /**
     * Specification
     * - This event will be used for spy_price_product entity changes
     *
     * @api
     */
    public const ENTITY_SPY_PRICE_PRODUCT_UPDATE = 'Entity.spy_price_product.update';

    /**
     * Specification
     * - This event will be used for spy_price_product entity deletion
     *
     * @api
     */
    public const ENTITY_SPY_PRICE_PRODUCT_DELETE = 'Entity.spy_price_product.delete';

    /**
     * Specification
     * - This event will be used for spy_price_product_store entity creation
     *
     * @api
     */
    public const ENTITY_SPY_PRICE_PRODUCT_STORE_CREATE = 'Entity.spy_price_product_store.create';

    /**
     * Specification
     * - This event will be used for spy_price_product_store entity changes
     *
     * @api
     */
    public const ENTITY_SPY_PRICE_PRODUCT_STORE_UPDATE = 'Entity.spy_price_product_store.update';

    /**
     * Specification
     * - This event will be used for spy_price_product_store entity deletion
     *
     * @api
     */
    public const ENTITY_SPY_PRICE_PRODUCT_STORE_DELETE = 'Entity.spy_price_product_store.delete';
}

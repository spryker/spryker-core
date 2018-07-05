<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Dependency;

interface ProductPackagingUnitEvents
{
    /**
     * Specification:
     * - This event is used for product_packaging_unit publishing.
     *
     * @api
     */
    public const PRODUCT_PACKAGING_UNIT_PUBLISH = 'ProductPackagingUnit.product_packaging_unit.publish';

    /**
     * Specification:
     * - This event is used for product_packaging_unit unpublishing.
     *
     * @api
     */
    public const PRODUCT_PACKAGING_UNIT_UNPUBLISH = 'ProductPackagingUnit.product_packaging_unit.unpublish';

    /**
     * Specification:
     * - This event is used for product_abstract_packaging_unit publishing.
     *
     * @api
     */
    public const PRODUCT_ABSTRACT_PACKAGING_PUBLISH = 'ProductPackagingUnit.product_packaging_unit.publish';

    /**
     * Specification:
     * - This event is used for product_abstract_packaging_unit unpublishing.
     *
     * @api
     */
    public const PRODUCT_ABSTRACT_PACKAGING_UNPUBLISH = 'ProductPackagingUnit.product_packaging_unit.unpublish';

    /**
     * Specification
     * - This events will be used for spy_product_packaging_unit_type update
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_PACKAGING_UNIT_TYPE_CREATE = 'Entity.spy_product_packaging_unit_type.create';

    /**
     * Specification
     * - This events will be used for spy_product_packaging_unit_type update
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_PACKAGING_UNIT_TYPE_UPDATE = 'Entity.spy_product_packaging_unit_type.update';

    /**
     * Specification
     * - This events will be used for spy_product_packaging_unit_type delete
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_PACKAGING_UNIT_TYPE_DELETE = 'Entity.spy_product_packaging_unit_type.delete';

    /**
     * Specification
     * - This events will be used for spy_product_packaging_unit update
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_PACKAGING_UNIT_CREATE = 'Entity.spy_product_packaging_unit.create';

    /**
     * Specification
     * - This events will be used for spy_product_packaging_unit update
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_PACKAGING_UNIT_UPDATE = 'Entity.spy_product_packaging_unit.update';

    /**
     * Specification
     * - This events will be used for spy_product_packaging_unit delete
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_PACKAGING_UNIT_DELETE = 'Entity.spy_product_packaging_unit.delete';

    /**
     * Specification
     * - This events will be used for spy_product_packaging_unit_amount update
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_PACKAGING_UNIT_AMOUNT_CREATE = 'Entity.spy_product_packaging_unit_amount.create';

    /**
     * Specification
     * - This events will be used for spy_product_packaging_unit_amount update
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_PACKAGING_UNIT_AMOUNT_UPDATE = 'Entity.spy_product_packaging_unit_amount.update';

    /**
     * Specification
     * - This events will be used for spy_product_packaging_unit_amount delete
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_PACKAGING_UNIT_AMOUNT_DELETE = 'Entity.spy_product_packaging_unit_amount.delete';

    /**
     * Specification
     * - This events will be used for spy_product_packaging_lead_product update
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_PACKAGING_LEAD_PRODUCT_CREATE = 'Entity.spy_product_packaging_lead_product.create';

    /**
     * Specification
     * - This events will be used for spy_product_packaging_lead_product update
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_PACKAGING_LEAD_PRODUCT_UPDATE = 'Entity.spy_product_packaging_lead_product.update';

    /**
     * Specification
     * - This events will be used for spy_product_packaging_lead_product delete
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_PACKAGING_LEAD_PRODUCT_DELETE = 'Entity.spy_product_packaging_lead_product.delete';
}

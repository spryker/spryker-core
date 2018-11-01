<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Dependency;

interface ProductMeasurementUnitEvents
{
   /**
    * Specification:
    * - This event is used for product_measurement_unit publishing.
    *
    * @api
    */
    public const PRODUCT_MEASUREMENT_UNIT_PUBLISH = 'ProductMeasurementUnit.product_measurement_unit.publish';

    /**
     * Specification:
     * - This event is used for product_measurement_unit unpublishing.
     *
     * @api
     */
    public const PRODUCT_MEASUREMENT_UNIT_UNPUBLISH = 'ProductMeasurementUnit.product_measurement_unit.unpublish';

    /**
     * Specification:
     * - This event is used for spy_product_measurement_unit entity creation.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_MEASUREMENT_UNIT_CREATE = 'Entity.spy_product_measurement_unit.create';

    /**
     * Specification:
     * - This event is used for spy_product_measurement_unit entity changes.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_MEASUREMENT_UNIT_UPDATE = 'Entity.spy_product_measurement_unit.update';

    /**
     * Specification:
     * - This event is used for spy_product_measurement_unit entity deletion.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_MEASUREMENT_UNIT_DELETE = 'Entity.spy_product_measurement_unit.delete';

    /**
     * Specification:
     * - This event is used for product_concrete_measurement_unit publishing.
     *
     * @api
     */
    public const PRODUCT_CONCRETE_MEASUREMENT_UNIT_PUBLISH = 'ProductMeasurementUnit.product_concrete_measurement_unit.publish';

    /**
     * Specification:
     * - This event is used for product_concrete_measurement_unit unpublishing.
     *
     * @api
     */
    public const PRODUCT_CONCRETE_MEASUREMENT_UNIT_UNPUBLISH = 'ProductMeasurementUnit.product_concrete_measurement_unit.unpublish';

    /**
     * Specification:
     * - This event is used for spy_product_measurement_base_unit entity creation.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_MEASUREMENT_BASE_UNIT_CREATE = 'Entity.spy_product_measurement_base_unit.create';

    /**
     * Specification:
     * - This event is used for spy_product_measurement_base_unit entity changes.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_MEASUREMENT_BASE_UNIT_UPDATE = 'Entity.spy_product_measurement_base_unit.update';

    /**
     * Specification:
     * - This event is used for spy_product_measurement_base_unit entity deletion.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_MEASUREMENT_BASE_UNIT_DELETE = 'Entity.spy_product_measurement_base_unit.delete';

    /**
     * Specification:
     * - This event is used for spy_product_measurement_sales_unit entity creation.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_MEASUREMENT_SALES_UNIT_CREATE = 'Entity.spy_product_measurement_sales_unit.create';

    /**
     * Specification:
     * - This event is used for spy_product_measurement_sales_unit entity changes.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_MEASUREMENT_SALES_UNIT_UPDATE = 'Entity.spy_product_measurement_sales_unit.update';

    /**
     * Specification:
     * - This event is used for spy_product_measurement_sales_unit entity deletion.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_MEASUREMENT_SALES_UNIT_DELETE = 'Entity.spy_product_measurement_sales_unit.delete';

    /**
     * Specification:
     * - This event is used for spy_product_measurement_sales_unit_store entity creation.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_MEASUREMENT_SALES_UNIT_STORE_CREATE = 'Entity.spy_product_measurement_sales_unit_store.create';

    /**
     * Specification:
     * - This event is used for spy_product_measurement_sales_unit_store entity changes.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_MEASUREMENT_SALES_UNIT_STORE_UPDATE = 'Entity.spy_product_measurement_sales_unit_store.update';

    /**
     * Specification:
     * - This event is used for spy_product_measurement_sales_unit_store entity deletion.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_MEASUREMENT_SALES_UNIT_STORE_DELETE = 'Entity.spy_product_measurement_sales_unit_store.delete';
}

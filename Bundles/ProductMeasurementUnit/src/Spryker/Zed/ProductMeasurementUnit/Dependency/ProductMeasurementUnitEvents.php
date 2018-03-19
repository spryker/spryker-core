<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Dependency;

interface ProductMeasurementUnitEvents
{
   /**
     * Specification
     * -
     *
     * @api
     */
    const PRODUCT_MEASUREMENT_UNIT_PUBLISH = 'ProductMeasurementUnit.product_measurement_unit.publish';

    /**
     * Specification
     * -
     *
     * @api
     */
    const PRODUCT_MEASUREMENT_UNIT_UNPUBLISH = 'ProductMeasurementUnit.product_measurement_unit.unpublish';

    /**
     * Specification
     * -
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_MEASUREMENT_UNIT_CREATE = 'Entity.spy_product_measurement_unit.create';

    /**
     * Specification
     * -
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_MEASUREMENT_UNIT_UPDATE = 'Entity.spy_product_measurement_unit.update';

    /**
     * Specification
     * -
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_MEASUREMENT_UNIT_DELETE = 'Entity.spy_product_measurement_unit.delete';

    /**
     * Specification
     * -
     *
     * @api
     */
    const PRODUCT_CONCRETE_MEASUREMENT_UNIT_PUBLISH = 'ProductMeasurementUnit.product_concrete_measurement_unit.publish';

    /**
     * Specification
     * -
     *
     * @api
     */
    const PRODUCT_CONCRETE_MEASUREMENT_UNIT_UNPUBLISH = 'ProductMeasurementUnit.product_concrete_measurement_unit.unpublish';

    /**
     * Specification
     * -
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_MEASUREMENT_BASE_UNIT_CREATE = 'Entity.spy_product_measurement_base_unit.create';

    /**
     * Specification
     * -
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_MEASUREMENT_BASE_UNIT_UPDATE = 'Entity.spy_product_measurement_base_unit.update';

    /**
     * Specification
     * -
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_MEASUREMENT_BASE_UNIT_DELETE = 'Entity.spy_product_measurement_base_unit.delete';

    /**
     * Specification
     * -
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_MEASUREMENT_SALES_UNIT_CREATE = 'Entity.spy_product_measurement_sales_unit.create';

    /**
     * Specification
     * -
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_MEASUREMENT_SALES_UNIT_UPDATE = 'Entity.spy_product_measurement_sales_unit.update';

    /**
     * Specification
     * -
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_MEASUREMENT_SALES_UNIT_DELETE = 'Entity.spy_product_measurement_sales_unit.delete';

    /**
     * Specification
     * -
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_MEASUREMENT_SALES_UNIT_STORE_CREATE = 'Entity.spy_product_measurement_sales_unit_store.create';

    /**
     * Specification
     * -
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_MEASUREMENT_SALES_UNIT_STORE_UPDATE = 'Entity.spy_product_measurement_sales_unit_store.update';

    /**
     * Specification
     * -
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_MEASUREMENT_SALES_UNIT_STORE_DELETE = 'Entity.spy_product_measurement_sales_unit_store.delete';
}

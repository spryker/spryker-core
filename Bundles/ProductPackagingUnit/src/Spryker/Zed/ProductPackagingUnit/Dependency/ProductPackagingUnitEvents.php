<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Dependency;

interface ProductPackagingUnitEvents
{
    /**
     * Specification
     * - This events will be used for spy_product_packaging_unit_type update
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_PACKAGING_UNIT_TYPE_CREATE = 'Entity.spy_product_packaging_unit_type.create';

    /**
     * Specification
     * - This events will be used for spy_product_packaging_unit_type update
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_PACKAGING_UNIT_TYPE_UPDATE = 'Entity.spy_product_packaging_unit_type.update';

    /**
     * Specification
     * - This events will be used for spy_product_packaging_unit_type delete
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_PACKAGING_UNIT_TYPE_DELETE = 'Entity.spy_product_packaging_unit_type.delete';
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Price\Dependency;

interface PriceEvents
{
    /**
     * Specification
     * - This events will be used for spy_price_product entity creation
     *
     * @api
     */
    const ENTITY_SPY_PRICE_PRODUCT_CREATE = 'Entity.spy_price_product.create';

    /**
     * Specification
     * - This events will be used for spy_price_product entity changes
     *
     * @api
     */
    const ENTITY_SPY_PRICE_PRODUCT_UPDATE = 'Entity.spy_price_product.update';

    /**
     * Specification
     * - This events will be used for spy_price_product entity deletion
     *
     * @api
     */
    const ENTITY_SPY_PRICE_PRODUCT_DELETE = 'Entity.spy_price_product.delete';

    /**
     * Specification
     * - This events will be used for spy_price_type entity creation
     *
     * @api
     */
    const ENTITY_SPY_PRICE_TYPE_CREATE = 'Entity.spy_price_type.create';

    /**
     * Specification
     * - This events will be used for spy_price_type entity changes
     *
     * @api
     */
    const ENTITY_SPY_PRICE_TYPE_UPDATE = 'Entity.spy_price_type.update';

    /**
     * Specification
     * - This events will be used for spy_price_type entity deletion
     *
     * @api
     */
    const ENTITY_SPY_PRICE_TYPE_DELETE = 'Entity.spy_price_type.delete';
}

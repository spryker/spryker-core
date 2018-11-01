<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantity\Dependency;

interface ProductQuantityEvents
{
   /**
    * Specification
    * - This event is used for product_quantity publishing.
    *
    * @api
    */
    public const PRODUCT_QUANTITY_PUBLISH = 'ProductQuantity.product_quantity.publish';

    /**
     * Specification
     * - This event is used for product_quantity unpublishing.
     *
     * @api
     */
    public const PRODUCT_QUANTITY_UNPUBLISH = 'ProductQuantity.product_quantity.unpublish';

    /**
     * Specification
     * - This event is used for spy_product_quantity entity creation.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_QUANTITY_CREATE = 'Entity.spy_product_quantity.create';

    /**
     * Specification
     * - This event is used for spy_product_quantity entity changes.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_QUANTITY_UPDATE = 'Entity.spy_product_quantity.update';

    /**
     * Specification
     * - This event is used for spy_product_quantity entity deletion.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_QUANTITY_DELETE = 'Entity.spy_product_quantity.delete';
}

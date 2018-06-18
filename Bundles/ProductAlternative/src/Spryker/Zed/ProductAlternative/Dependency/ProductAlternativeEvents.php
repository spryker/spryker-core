<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Dependency;

interface ProductAlternativeEvents
{
    /**
 * Specification
 * - This events will be used for spy_product_alternative entity creation
 *
 * @api
 */
    public const ENTITY_SPY_PRODUCT_ALTERNATIVE_CREATE = 'Entity.spy_product_alternative.create';

    /**
     * Specification
     * - This events will be used for spy_product_alternative entity changes
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_ALTERNATIVE_UPDATE = 'Entity.spy_product_alternative.update';

    /**
     * Specification
     * - This events will be used for spy_product_alternative entity deletion
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_ALTERNATIVE_DELETE = 'Entity.spy_product_alternative.delete';

    /**
     * Specification:
     * - This event is used for product_alternative publishing.
     *
     * @api
     */
    public const PRODUCT_ALTERNATIVE_PUBLISH = 'ProductAlternative.product_alternative.publish';

    /**
     * Specification:
     * - This event is used for product_alternative unpublishing.
     *
     * @api
     */
    public const PRODUCT_ALTERNATIVE_UNPUBLISH = 'ProductAlternative.product_alternative.unpublish';

    /**
     * Specification
     * - This events will be used for spy_product_alternative entity creation
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_REPLACEMENT_CREATE = 'Entity.spy_product_alternative.create';

    /**
     * Specification
     * - This events will be used for spy_product_alternative entity changes
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_REPLACEMENT_UPDATE = 'Entity.spy_product_alternative.update';

    /**
     * Specification
     * - This events will be used for spy_product_alternative entity deletion
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_REPLACEMENT_DELETE = 'Entity.spy_product_alternative.delete';

    /**
     * Specification:
     * - This event is used for product_alternative publishing.
     *
     * @api
     */
    public const PRODUCT_REPLACEMENT_PUBLISH = 'ProductAlternative.product_alternative.publish';

    /**
     * Specification:
     * - This event is used for product_alternative unpublishing.
     *
     * @api
     */
    public const PRODUCT_REPLACEMENT_UNPUBLISH = 'ProductAlternative.product_alternative.unpublish';

}

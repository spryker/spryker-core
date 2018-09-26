<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSet\Dependency;

interface ProductSetEvents
{
    /**
     * Specification
     * - This events will be used for product_set publishing
     *
     * @api
     */
    public const PRODUCT_SET_PUBLISH = 'ProductSet.product_set.publish';

    /**
     * Specification
     * - This events will be used for product_set un-publishing
     *
     * @api
     */
    public const PRODUCT_SET_UNPUBLISH = 'ProductSet.product_set.unpublish';

    /**
     * Specification
     * - This events will be used for spy_product_set entity creation
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_SET_CREATE = 'Entity.spy_product_set.create';

    /**
     * Specification
     * - This events will be used for spy_product_set entity changes
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_SET_UPDATE = 'Entity.spy_product_set.update';

    /**
     * Specification
     * - This events will be used for spy_product_set entity deletion
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_SET_DELETE = 'Entity.spy_product_set.delete';

    /**
     * Specification
     * - This events will be used for spy_product_abstract_set entity creation
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_ABSTRACT_SET_CREATE = 'Entity.spy_product_abstract_set.create';

    /**
     * Specification
     * - This events will be used for spy_product_abstract_set entity changes
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_ABSTRACT_SET_UPDATE = 'Entity.spy_product_abstract_set.update';

    /**
     * Specification
     * - This events will be used for spy_product_abstract_set entity deletion
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_ABSTRACT_SET_DELETE = 'Entity.spy_product_abstract_set.delete';

    /**
     * Specification
     * - This events will be used for spy_product_set_data entity creation
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_SET_DATA_CREATE = 'Entity.spy_product_set_data.create';

    /**
     * Specification
     * - This events will be used for spy_product_set_data entity changes
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_SET_DATA_UPDATE = 'Entity.spy_product_set_data.update';

    /**
     * Specification
     * - This events will be used for spy_product_set_data entity deletion
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_SET_DATA_DELETE = 'Entity.spy_product_set_data.delete';
}

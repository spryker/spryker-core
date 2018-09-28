<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Dependency;

interface ProductOptionEvents
{
    /**
     * Specification
     * - This events will be used for product_abstract_option publishing
     *
     * @api
     */
    public const PRODUCT_ABSTRACT_PRODUCT_OPTION_PUBLISH = 'ProductOption.product_abstract_option.publish';

    /**
     * Specification
     * - This events will be used for product_abstract_option publishing
     *
     * @api
     */
    public const PRODUCT_ABSTRACT_PRODUCT_OPTION_UNPUBLISH = 'ProductOption.product_abstract_option.unpublish';

    /**
     * Specification
     * - This events will be used for spy_product_abstract_product_option_group entity creation
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_ABSTRACT_PRODUCT_OPTION_GROUP_CREATE = 'Entity.spy_product_abstract_product_option_group.create';

    /**
     * Specification
     * - This events will be used for spy_product_abstract_product_option_group entity changes
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_ABSTRACT_PRODUCT_OPTION_GROUP_UPDATE = 'Entity.spy_product_abstract_product_option_group.update';

    /**
     * Specification
     * - This events will be used for spy_product_abstract_product_option_group entity deletion
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_ABSTRACT_PRODUCT_OPTION_GROUP_DELETE = 'Entity.spy_product_abstract_product_option_group.delete';

    /**
     * Specification
     * - This events will be used for spy_product_option_group entity creation
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_OPTION_GROUP_CREATE = 'Entity.spy_product_option_group.create';

    /**
     * Specification
     * - This events will be used for spy_product_option_group entity changes
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_OPTION_GROUP_UPDATE = 'Entity.spy_product_option_group.update';

    /**
     * Specification
     * - This events will be used for spy_product_option_group entity deletion
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_OPTION_GROUP_DELETE = 'Entity.spy_product_option_group.delete';

    /**
     * Specification
     * - This events will be used for spy_product_option_value entity creation
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_OPTION_VALUE_CREATE = 'Entity.spy_product_option_value.create';

    /**
     * Specification
     * - This events will be used for spy_product_option_value entity changes
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_OPTION_VALUE_UPDATE = 'Entity.spy_product_option_value.update';

    /**
     * Specification
     * - This events will be used for spy_product_option_value entity deletion
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_OPTION_VALUE_DELETE = 'Entity.spy_product_option_value.delete';

    /**
     * Specification
     * - This events will be used for spy_product_option_value_price entity creation
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_OPTION_VALUE_PRICE_CREATE = 'Entity.spy_product_option_value_price.create';

    /**
     * Specification
     * - This events will be used for spy_product_option_value_price entity change
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_OPTION_VALUE_PRICE_UPDATE = 'Entity.spy_product_option_value_price.update';

    /**
     * Specification
     * - This events will be used for spy_product_option_value_price entity deletion
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_OPTION_VALUE_PRICE_DELETE = 'Entity.spy_product_option_value_price.delete';
}

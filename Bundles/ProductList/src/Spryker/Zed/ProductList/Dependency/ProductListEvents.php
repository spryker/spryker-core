<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Dependency;

interface ProductListEvents
{
    /**
     * Specification:
     * - This event is used for spy_product_list publishing.
     *
     * @api
     */
    public const PRODUCT_LIST_PUBLISH = 'ProductList.spy_product_list.publish';

    /**
     * Specification:
     * - This event is used for spy_product_list unpublishing.
     *
     * @api
     */
    public const PRODUCT_LIST_UNPUBLISH = 'ProductList.spy_product_list.unpublish';

    /**
     * Specification
     * - This event is used for spy_product_list entity creation
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_LIST_CREATE = 'Entity.spy_product_list.create';

    /**
     * Specification
     * - This event is used for spy_product_list entity changes
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_LIST_UPDATE = 'Entity.spy_product_list.update';

    /**
     * Specification
     * - This event is used for spy_product_list entity deletion
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_LIST_DELETE = 'Entity.spy_product_list.delete';

    /**
     * Specification:
     * - This event is used for spy_product_list_product_concrete publishing.
     *
     * @api
     */
    public const PRODUCT_LIST_PRODUCT_CONCRETE_PUBLISH = 'ProductList.spy_product_list_product_concrete.publish';

    /**
     * Specification:
     * - This event is used for spy_product_list_product_concrete unpublishing.
     *
     * @api
     */
    public const PRODUCT_LIST_PRODUCT_CONCRETE_UNPUBLISH = 'ProductList.spy_product_list_product_concrete.unpublish';

    /**
     * Specification
     * - This event is used for spy_product_list_product_concrete entity creation
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_LIST_PRODUCT_CONCRETE_CREATE = 'Entity.spy_product_list_product_concrete.create';

    /**
     * Specification
     * - This event is used for spy_product_list_product_concrete entity changes
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_LIST_PRODUCT_CONCRETE_UPDATE = 'Entity.spy_product_list_product_concrete.update';

    /**
     * Specification
     * - This event is used for spy_product_list_product_concrete entity deletion
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_LIST_PRODUCT_CONCRETE_DELETE = 'Entity.spy_product_list_product_concrete.delete';

    /**
     * Specification:
     * - This event is used for spy_product_list_category publishing.
     *
     * @api
     */
    public const PRODUCT_LIST_CATEGORY_PUBLISH = 'ProductList.spy_product_list_category.publish';

    /**
     * Specification:
     * - This event is used for spy_product_list_category unpublishing.
     *
     * @api
     */
    public const PRODUCT_LIST_CATEGORY_UNPUBLISH = 'ProductList.spy_product_list_category.unpublish';

    /**
     * Specification
     * - This event is used for spy_product_list_category entity creation
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_LIST_CATEGORY_CREATE = 'Entity.spy_product_list_category.create';

    /**
     * Specification
     * - This event is used for spy_product_list_category entity changes
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_LIST_CATEGORY_UPDATE = 'Entity.spy_product_list_category.update';

    /**
     * Specification
     * - This event is used for spy_product_list_category entity deletion
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_LIST_CATEGORY_DELETE = 'Entity.spy_product_list_category.delete';
}

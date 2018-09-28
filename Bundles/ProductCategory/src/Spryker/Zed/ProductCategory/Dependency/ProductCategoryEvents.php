<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Dependency;

interface ProductCategoryEvents
{
    public const PRODUCT_CATEGORY_ASSIGNED = 'ProductCategory.product.assigned';
    public const PRODUCT_CATEGORY_UNASSIGNED = 'ProductCategory.product.unassigned';

    /**
     * Specification
     * - This events will be used for product_category publishing
     *
     * @api
     */
    public const PRODUCT_CATEGORY_PUBLISH = 'ProductCategory.category.publish';

    /**
     * Specification
     * - This events will be used for product_category un-publishing
     *
     * @api
     */
    public const PRODUCT_CATEGORY_UNPUBLISH = 'ProductCategory.category.unpublish';

    /**
     * Specification
     * - This events will be used for spy_product_category entity creation
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_CATEGORY_CREATE = 'Entity.spy_product_category.create';

    /**
     * Specification
     * - This events will be used for spy_product_category entity changes
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_CATEGORY_UPDATE = 'Entity.spy_product_category.update';

    /**
     * Specification
     * - This events will be used for spy_product_category entity deletion
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_CATEGORY_DELETE = 'Entity.spy_product_category.delete';
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilter\Dependency;

interface ProductCategoryFilterEvents
{
    /**
     * Specification
     * - This events will be used for spy_product_category_filter entity creation
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_CATEGORY_FILTER_CREATE = 'Entity.spy_product_category_filter.create';

    /**
     * Specification
     * - This events will be used for spy_product_category_filter entity changes
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_CATEGORY_FILTER_UPDATE = 'Entity.spy_product_category_filter.update';

    /**
     * Specification
     * - This events will be used for spy_product_category_filter entity deletion
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_CATEGORY_FILTER_DELETE = 'Entity.spy_product_category_filter.delete';
}

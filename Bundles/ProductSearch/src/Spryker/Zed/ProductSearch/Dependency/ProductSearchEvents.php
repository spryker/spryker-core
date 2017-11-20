<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Dependency;

interface ProductSearchEvents
{
    /**
     * Specification
     * - This events will be used for spy_product_search_attribute entity creation
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_SEARCH_ATTRIBUTE_CREATE = 'Entity.spy_product_search_attribute.create';

    /**
     * Specification
     * - This events will be used for spy_product_search_attribute entity changes
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_SEARCH_ATTRIBUTE_UPDATE = 'Entity.spy_product_search_attribute.update';

    /**
     * Specification
     * - This events will be used for spy_product_search_attribute entity deletion
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_SEARCH_ATTRIBUTE_DELETE = 'Entity.spy_product_search_attribute.delete';
}

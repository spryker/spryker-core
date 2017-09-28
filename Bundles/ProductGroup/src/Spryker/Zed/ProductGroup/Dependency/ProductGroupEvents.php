<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductGroup\Dependency;

interface ProductGroupEvents
{

    /**
     * Specification
     * - This events will be used for spy_product_abstract_group entity creation
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_ABSTRACT_GROUP_CREATE = 'Entity.spy_product_abstract_group.create';

    /**
     * Specification
     * - This events will be used for spy_product_abstract_group entity changes
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_ABSTRACT_GROUP_UPDATE = 'Entity.spy_product_abstract_group.update';

    /**
     * Specification
     * - This events will be used for spy_product_abstract_group entity deletion
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_ABSTRACT_GROUP_DELETE = 'Entity.spy_product_abstract_group.delete';

}

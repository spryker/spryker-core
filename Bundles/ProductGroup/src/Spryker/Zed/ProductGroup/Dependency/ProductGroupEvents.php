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
     * - This events will be used for product_abstract_group publishing
     *
     * @api
     */
    public const PRODUCT_GROUP_PUBLISH = 'ProductGroup.product_abstract_group.publish';

    /**
     * Specification
     * - This events will be used for product_abstract_group un-publishing
     *
     * @api
     */
    public const PRODUCT_GROUP_UNPUBLISH = 'ProductGroup.product_abstract_group.unpublish';

    /**
     * Specification
     * - This events will be used for spy_product_abstract_group entity creation
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_ABSTRACT_GROUP_CREATE = 'Entity.spy_product_abstract_group.create';

    /**
     * Specification
     * - This events will be used for spy_product_abstract_group entity changes
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_ABSTRACT_GROUP_UPDATE = 'Entity.spy_product_abstract_group.update';

    /**
     * Specification
     * - This events will be used for spy_product_abstract_group entity deletion
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_ABSTRACT_GROUP_DELETE = 'Entity.spy_product_abstract_group.delete';
}

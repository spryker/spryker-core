<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Dependency;

interface ProductRelationEvents
{
    /**
     * Specification
     * - This events will be used for spy_product_relation_product_abstract entity creation
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_RELATION_PRODUCT_ABSTRACT_CREATE = 'Entity.spy_product_relation_product_abstract.create';

    /**
     * Specification
     * - This events will be used for spy_product_relation_product_abstract entity changes
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_RELATION_PRODUCT_ABSTRACT_UPDATE = 'Entity.spy_product_relation_product_abstract.update';

    /**
     * Specification
     * - This events will be used for spy_product_relation_product_abstract entity deletion
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_RELATION_PRODUCT_ABSTRACT_DELETE = 'Entity.spy_product_relation_product_abstract.delete';

    /**
     * Specification
     * - This events will be used for spy_product_relation entity creation
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_RELATION_CREATE = 'Entity.spy_product_relation.create';

    /**
     * Specification
     * - This events will be used for spy_product_relation entity changes
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_RELATION_UPDATE = 'Entity.spy_product_relation.update';

    /**
     * Specification
     * - This events will be used for spy_product_relation entity deletion
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_RELATION_DELETE = 'Entity.spy_product_relation.delete';
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Dependency;

interface ProductRelationEvents
{

    const PRODUCT_RELATION_ABSTRACT_PUBLISH = 'ProductRelation.productAbstract.publish';
    const PRODUCT_RELATION_ABSTRACT_UNPUBLISH = 'ProductRelation.productAbstract.unpublish';

    const ENTITY_SPY_PRODUCT_RELATION_PRODUCT_ABSTRACT_CREATE = 'Entity.spy_product_relation_product_abstract.create';
    const ENTITY_SPY_PRODUCT_RELATION_PRODUCT_ABSTRACT_UPDATE = 'Entity.spy_product_relation_product_abstract.update';
    const ENTITY_SPY_PRODUCT_RELATION_PRODUCT_ABSTRACT_DELETE = 'Entity.spy_product_relation_product_abstract.delete';

    const ENTITY_SPY_PRODUCT_RELATION_CREATE = 'Entity.spy_product_relation.create';
    const ENTITY_SPY_PRODUCT_RELATION_UPDATE = 'Entity.spy_product_relation.update';
    const ENTITY_SPY_PRODUCT_RELATION_DELETE = 'Entity.spy_product_relation.delete';

}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductGroup\Dependency;

interface ProductGroupEvents
{

    const PRODUCT_GROUP_ABSTRACT_PUBLISH = 'ProductGroup.productAbstract.publish';
    const PRODUCT_GROUP_ABSTRACT_UNPUBLISH = 'ProductGroup.productAbstract.unpublish';

    const ENTITY_SPY_PRODUCT_ABSTRACT_GROUP_CREATE = 'Entity.spy_product_abstract_group.create';
    const ENTITY_SPY_PRODUCT_ABSTRACT_GROUP_UPDATE = 'Entity.spy_product_abstract_group.update';
    const ENTITY_SPY_PRODUCT_ABSTRACT_GROUP_DELETE = 'Entity.spy_product_abstract_group.delete';

}

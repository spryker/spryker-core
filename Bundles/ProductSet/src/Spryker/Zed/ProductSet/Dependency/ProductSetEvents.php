<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSet\Dependency;

interface ProductSetEvents
{

    const PRODUCT_SET_PUBLISH = 'ProductSet.entity.publish';
    const PRODUCT_SET_UNPUBLISH = 'ProductSet.entity.unpublish';

    const ENTITY_SPY_PRODUCT_SET_CREATE = 'Entity.spy_product_set.create';
    const ENTITY_SPY_PRODUCT_SET_UPDATE = 'Entity.spy_product_set.update';
    const ENTITY_SPY_PRODUCT_SET_DELETE = 'Entity.spy_product_set.delete';

    const ENTITY_SPY_PRODUCT_ABSTRACT_SET_CREATE = 'Entity.spy_product_abstract_set.create';
    const ENTITY_SPY_PRODUCT_ABSTRACT_SET_UPDATE = 'Entity.spy_product_abstract_set.update';
    const ENTITY_SPY_PRODUCT_ABSTRACT_SET_DELETE = 'Entity.spy_product_abstract_set.delete';

    const ENTITY_SPY_PRODUCT_SET_DATA_CREATE = 'Entity.spy_product_set_data.create';
    const ENTITY_SPY_PRODUCT_SET_DATA_UPDATE = 'Entity.spy_product_set_data.update';
    const ENTITY_SPY_PRODUCT_SET_DATA_DELETE = 'Entity.spy_product_set_data.delete';

}

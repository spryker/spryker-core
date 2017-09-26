<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Dependency;

interface ProductOptionEvents
{

    const PRODUCT_OPTION_ABSTRACT_PUBLISH = 'ProductOption.productAbstract.publish';
    const PRODUCT_OPTION_ABSTRACT_UNPUBLISH = 'ProductOption.productAbstract.unpublish';

    const ENTITY_SPY_PRODUCT_ABSTRACT_PRODUCT_OPTION_GROUP_CREATE = 'Entity.spy_product_abstract_product_option_group.create';
    const ENTITY_SPY_PRODUCT_ABSTRACT_PRODUCT_OPTION_GROUP_UPDATE = 'Entity.spy_product_abstract_product_option_group.update';
    const ENTITY_SPY_PRODUCT_ABSTRACT_PRODUCT_OPTION_GROUP_DELETE = 'Entity.spy_product_abstract_product_option_group.delete';

    const ENTITY_SPY_PRODUCT_OPTION_GROUP_CREATE = 'Entity.spy_product_option_group.create';
    const ENTITY_SPY_PRODUCT_OPTION_GROUP_UPDATE = 'Entity.spy_product_option_group.update';
    const ENTITY_SPY_PRODUCT_OPTION_GROUP_DELETE = 'Entity.spy_product_option_group.delete';

    const ENTITY_SPY_PRODUCT_OPTION_VALUE_CREATE = 'Entity.spy_product_option_value.create';
    const ENTITY_SPY_PRODUCT_OPTION_VALUE_UPDATE = 'Entity.spy_product_option_value.update';
    const ENTITY_SPY_PRODUCT_OPTION_VALUE_DELETE = 'Entity.spy_product_option_value.delete';

}

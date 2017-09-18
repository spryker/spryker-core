<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Dependency;

use Orm\Zed\ProductOption\Persistence\Map\SpyProductAbstractProductOptionGroupTableMap;
use Orm\Zed\ProductOption\Persistence\Map\SpyProductOptionGroupTableMap;
use Orm\Zed\ProductOption\Persistence\Map\SpyProductOptionValueTableMap;

interface ProductOptionEvents
{

    const PRODUCT_OPTION_ABSTRACT_PUBLISH = 'ProductOption.productAbstract.publish';
    const PRODUCT_OPTION_ABSTRACT_UNPUBLISH = 'ProductOption.productAbstract.unpublish';

    const ENTITY_SPY_PRODUCT_ABSTRACT_PRODUCT_OPTION_GROUP_CREATE = 'Entity.' . SpyProductAbstractProductOptionGroupTableMap::TABLE_NAME . '.create';
    const ENTITY_SPY_PRODUCT_ABSTRACT_PRODUCT_OPTION_GROUP_UPDATE = 'Entity.' . SpyProductAbstractProductOptionGroupTableMap::TABLE_NAME . '.update';
    const ENTITY_SPY_PRODUCT_ABSTRACT_PRODUCT_OPTION_GROUP_DELETE = 'Entity.' . SpyProductAbstractProductOptionGroupTableMap::TABLE_NAME . '.delete';

    const ENTITY_SPY_PRODUCT_OPTION_GROUP_CREATE = 'Entity.' . SpyProductOptionGroupTableMap::TABLE_NAME . '.create';
    const ENTITY_SPY_PRODUCT_OPTION_GROUP_UPDATE = 'Entity.' . SpyProductOptionGroupTableMap::TABLE_NAME . '.update';
    const ENTITY_SPY_PRODUCT_OPTION_GROUP_DELETE = 'Entity.' . SpyProductOptionGroupTableMap::TABLE_NAME . '.delete';

    const ENTITY_SPY_PRODUCT_OPTION_VALUE_CREATE = 'Entity.' . SpyProductOptionValueTableMap::TABLE_NAME . '.create';
    const ENTITY_SPY_PRODUCT_OPTION_VALUE_UPDATE = 'Entity.' . SpyProductOptionValueTableMap::TABLE_NAME . '.update';
    const ENTITY_SPY_PRODUCT_OPTION_VALUE_DELETE = 'Entity.' . SpyProductOptionValueTableMap::TABLE_NAME . '.delete';

}

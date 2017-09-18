<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Price\Dependency;

use Orm\Zed\Price\Persistence\Map\SpyPriceProductTableMap;
use Orm\Zed\Price\Persistence\Map\SpyPriceTypeTableMap;

interface PriceEvents
{

    const PRICE_ABSTRACT_PUBLISH = 'Price.productAbstract.publish';
    const PRICE_ABSTRACT_UNPUBLISH = 'Price.productAbstract.unpublish';

    const PRICE_PRODUCT_PUBLISH = 'Price.product.publish';
    const PRICE_PRODUCT_UNPUBLISH = 'Price.product.unpublish';

    const ENTITY_SPY_PRICE_PRODUCT_CREATE = 'Entity.' . SpyPriceProductTableMap::TABLE_NAME . '.create';
    const ENTITY_SPY_PRICE_PRODUCT_UPDATE = 'Entity.' . SpyPriceProductTableMap::TABLE_NAME . '.update';
    const ENTITY_SPY_PRICE_PRODUCT_DELETE = 'Entity.' . SpyPriceProductTableMap::TABLE_NAME . '.delete';

    const ENTITY_SPY_PRICE_TYPE_CREATE = 'Entity.' . SpyPriceTypeTableMap::TABLE_NAME . '.create';
    const ENTITY_SPY_PRICE_TYPE_UPDATE = 'Entity.' . SpyPriceTypeTableMap::TABLE_NAME . '.update';
    const ENTITY_SPY_PRICE_TYPE_DELETE = 'Entity.' . SpyPriceTypeTableMap::TABLE_NAME . '.delete';

}

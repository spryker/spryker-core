<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Price\Dependency;

interface PriceEvents
{

    const PRICE_ABSTRACT_PUBLISH = 'Price.productAbstract.publish';
    const PRICE_ABSTRACT_UNPUBLISH = 'Price.productAbstract.unpublish';

    const PRICE_PRODUCT_PUBLISH = 'Price.product.publish';
    const PRICE_PRODUCT_UNPUBLISH = 'Price.product.unpublish';

    const ENTITY_SPY_PRICE_PRODUCT_CREATE = 'Entity.spy_price_product.create';
    const ENTITY_SPY_PRICE_PRODUCT_UPDATE = 'Entity.spy_price_product.update';
    const ENTITY_SPY_PRICE_PRODUCT_DELETE = 'Entity.spy_price_product.delete';

    const ENTITY_SPY_PRICE_TYPE_CREATE = 'Entity.spy_price_type.create';
    const ENTITY_SPY_PRICE_TYPE_UPDATE = 'Entity.spy_price_type.update';
    const ENTITY_SPY_PRICE_TYPE_DELETE = 'Entity.spy_price_type.delete';

}

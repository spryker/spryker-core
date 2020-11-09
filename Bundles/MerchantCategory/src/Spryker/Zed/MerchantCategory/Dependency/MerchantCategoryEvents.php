<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCategory\Dependency;

class MerchantCategoryEvents
{
    /**
     * This events will be used for spy_merchant_category entity changes.
     */
    public const ENTITY_SPY_MERCHANT_CATEGORY_UPDATE = 'Entity.spy_merchant_category.update';

    /**
     * This events will be used for spy_merchant_category entity creation.
     */
    public const ENTITY_SPY_MERCHANT_CATEGORY_CREATE = 'Entity.spy_merchant_category.create';

    /**
     * This events will be used for spy_merchant_category entity deletetion.
     */
    public const ENTITY_SPY_MERCHANT_CATEGORY_DELETE = 'Entity.spy_merchant_category.delete';

    /**
     * This events will be used for spy_merchant_category publishing.
     */
    public const MERCHANT_CATEGORY_PUBLISH = 'MerchantCategory.merchant_category.publish';
}

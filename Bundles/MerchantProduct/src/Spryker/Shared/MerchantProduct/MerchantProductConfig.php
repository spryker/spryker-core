<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\MerchantProduct;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class MerchantProductConfig extends AbstractSharedConfig
{
    /**
     * Specification
     * - This events will be used for spy_merchant publishing.
     *
     * @api
     *
     * @var string
     */
    public const MERCHANT_PUBLISH = 'Merchant.merchant.publish';

    /**
     * Specification
     * - This events will be used for spy_merchant entity changes.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_MERCHANT_UPDATE = 'Entity.spy_merchant.update';

    /**
     * Specification
     * - This event will be used for product abstract publishing
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_ABSTRACT_PUBLISH = 'Product.product_abstract_search.publish';
}

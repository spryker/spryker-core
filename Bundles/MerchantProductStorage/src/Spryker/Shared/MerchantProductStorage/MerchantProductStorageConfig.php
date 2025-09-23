<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\MerchantProductStorage;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class MerchantProductStorageConfig extends AbstractBundleConfig
{
    /**
     * Specification
     * - This event will be used for merchant product abstract publishing.
     *
     * @api
     *
     * @var string
     */
    public const MERCHANT_PRODUCT_ABSTRACT_PUBLISH = 'MerchantProductAbstract.publish';

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
     * Specification:
     * - Merchant product resource name, used for key generating.
     *
     * @api
     *
     * @var string
     */
    public const MERCHANT_PRODUCT_RESOURCE_NAME = 'merchant_product';

    /**
     * Specification
     * - This events will be used for spy_merchant publishing.
     *
     * @api
     *
     * @var string
     */
    public const MERCHANT_PUBLISH = 'Merchant.merchant.publish';
}

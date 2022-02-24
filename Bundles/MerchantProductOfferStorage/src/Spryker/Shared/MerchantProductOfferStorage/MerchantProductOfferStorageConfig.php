<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\MerchantProductOfferStorage;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
class MerchantProductOfferStorageConfig
{
    /**
     * Specification
     * - This events will be used for spy_merchant publishing.
     *
     * @api
     *
     * @uses \Spryker\Zed\Merchant\Dependency\MerchantEvents::MERCHANT_PUBLISH
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
     * @uses \Spryker\Zed\Merchant\Dependency\MerchantEvents::ENTITY_SPY_MERCHANT_UPDATE
     *
     * @var string
     */
    public const ENTITY_SPY_MERCHANT_UPDATE = 'Entity.spy_merchant.update';
}

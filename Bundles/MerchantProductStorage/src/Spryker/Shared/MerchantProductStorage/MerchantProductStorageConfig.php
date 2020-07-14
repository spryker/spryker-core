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
     */
    public const MERCHANT_PRODUCT_ABSTRACT_PUBLISH = 'MerchantProductAbstract.publish';

    /**
     * Specification
     * - This events will be used for spy_merchant entity changes.
     *
     * @api
     */
    public const ENTITY_SPY_MERCHANT_UPDATE = 'Entity.spy_merchant.update';
}

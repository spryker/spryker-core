<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\MerchantProductOptionStorage;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class MerchantProductOptionStorageConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - This events will be used for `spy_merchant_product_option_group` publishing.
     *
     * @api
     * @var string
     */
    public const MERCHANT_PRODUCT_OPTION_GROUP_PUBLISH = 'MerchantProductOption.group.publish';

    /**
     * Specification:
     * - This events will be used for `spy_merchant_product_option_group` entity creation.
     *
     * @api
     * @var string
     */
    public const ENTITY_SPY_MERCHANT_PRODUCT_OPTION_GROUP_CREATE = 'Entity.spy_merchant_product_option_group.create';

    /**
     * Specification:
     * - This events will be used for `spy_merchant_product_option_group` entity changes.
     *
     * @api
     * @var string
     */
    public const ENTITY_SPY_MERCHANT_PRODUCT_OPTION_GROUP_UPDATE = 'Entity.spy_merchant_product_option_group.update';
}

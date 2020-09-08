<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundleCartsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class ConfigurableBundleCartsRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_CONFIGURED_BUNDLES = 'configured-bundles';
    public const RESOURCE_GUEST_CONFIGURED_BUNDLES = 'guest-configured-bundles';

    /**
     * @uses \Spryker\Glue\CartsRestApi\CartsRestApiConfig::RESOURCE_CARTS
     */
    public const RESOURCE_CARTS = 'carts';

    /**
     * @uses \Spryker\Glue\CartsRestApi\CartsRestApiConfig::RESPONSE_CODE_CART_ID_MISSING
     */
    public const RESPONSE_CODE_CART_ID_MISSING = '104';

    /**
     * @uses \Spryker\Glue\CartsRestApi\CartsRestApiConfig::EXCEPTION_MESSAGE_CART_ID_MISSING
     */
    public const EXCEPTION_MESSAGE_CART_ID_MISSING = 'Cart uuid is missing.';

    public const RESPONSE_CODE_VALIDATION = '666'; // TODO: replace to correct one

    /**
     * @api
     *
     * @return array
     */
    public function getErrorIdentifierToRestErrorMapping(): array
    {
        return [];
    }
}

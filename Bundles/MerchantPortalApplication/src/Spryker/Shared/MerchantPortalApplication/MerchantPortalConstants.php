<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\MerchantPortalApplication;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface MerchantPortalConstants
{
    /**
     * Base URL for Merchant Portal including scheme and port (e.g. http://mp.de.demoshop.local:9080)
     *
     * @api
     * @var string
     */
    public const BASE_URL_MP = 'MERCHANT_PORTAL_APPLICATION:BASE_URL_MP';

    /**
     * - Enables/disables global setting for merchant portal debug mode.
     * - Defaults to false.
     *
     * @api
     * @var string
     */
    public const ENABLE_APPLICATION_DEBUG = 'MERCHANT_PORTAL_APPLICATION:ENABLE_APPLICATION_DEBUG';
}

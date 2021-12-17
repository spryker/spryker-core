<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\GlueStorefrontApiApplication;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface GlueStorefrontApiApplicationConstants
{
    /**
     * Specification:
     * - Enables/disables global setting for debug mode.
     * - Defaults to false.
     *
     * @api
     *
     * @var string
     */
    public const ENABLE_APPLICATION_DEBUG = 'GLUE_STOREFRONT_API_APPLICATION:ENABLE_APPLICATION_DEBUG';

    /**
     * Specification:
     * - Contains the host that the Storefront API serves
     *
     * @api
     *
     * @var string
     */
    public const GLUE_STOREFRONT_API_HOST = 'GLUE_STOREFRONT_API_APPLICATION:GLUE_STOREFRONT_API_HOST';
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\GlueApplication;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface GlueApplicationConstants
{
    /**
     * Specification:
     * - The domain name used for Glue application.
     *
     * @api
     *
     * @var string
     */
    public const GLUE_APPLICATION_DOMAIN = 'GLUE_APPLICATION_DOMAIN';

    /**
     *  Specification:
     *  - If rest debug is enabled, will show exception stack traces instead of 500 errors.
     *
     * @api
     *
     * @var string
     */
    public const GLUE_APPLICATION_REST_DEBUG = 'GLUE_APPLICATION_REST_DEBUG';

    /**
     * Specification:
     * - The domain name returned as allowed in 'access-control-allow-origin' CORS headers.
     * - Ether '*' or single domain should be passed, due to CORS policy.
     *
     * @api
     *
     * @var string
     */
    public const GLUE_APPLICATION_CORS_ALLOW_ORIGIN = 'GLUE_APPLICATION_CORS_ALLOW_ORIGIN';

    /**
     * Specification:
     * - Enables/disables global setting for debug mode.
     * - Defaults to false.
     * - Value can be retrieved from the Container.
     *
     * @api
     *
     * @var string
     */
    public const ENABLE_APPLICATION_DEBUG = 'GLUE_APPLICATION:ENABLE_APPLICATION_DEBUG';
}

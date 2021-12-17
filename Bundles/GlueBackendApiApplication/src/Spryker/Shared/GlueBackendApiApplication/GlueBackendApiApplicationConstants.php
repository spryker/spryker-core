<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\GlueBackendApiApplication;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface GlueBackendApiApplicationConstants
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
    public const ENABLE_APPLICATION_DEBUG = 'GLUE_BACKEND_API_APPLICATION:ENABLE_APPLICATION_DEBUG';

    /**
     * Specification:
     * - Contains the host that the Backend API serves
     *
     * @api
     *
     * @var string
     */
    public const GLUE_BACKEND_API_HOST = 'GLUE_BACKEND_API_APPLICATION:GLUE_BACKEND_API_HOST';
}

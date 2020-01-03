<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Api;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface ApiConstants
{
    /**
     * Specification:
     * - Enables the mode when API response is extended with request parameters and stacktrace.
     *
     * @api
     */
    public const ENABLE_API_DEBUG = 'API:ENABLE_API_DEBUG';
}

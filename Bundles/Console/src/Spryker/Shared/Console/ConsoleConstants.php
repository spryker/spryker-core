<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Console;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface ConsoleConstants
{
    /**
     * Specification:
     * - If option set to true, the console commands will return the exception error code.
     * - If option set to false, the console commands will throw the exception, if any.
     *
     * @api
     */
    public const CATCH_EXCEPTIONS = 'CONSOLE:CATCH_EXCEPTIONS';
}

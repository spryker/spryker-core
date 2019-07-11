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
     * - If true, the console commands will throw the exception, if any, always returning code `1` for errors.
     * - If false, the console commands will return the exception code.
     *
     * @api
     */
    public const CATCH_EXCEPTIONS = 'CONSOLE:CATCH_EXCEPTIONS';
}

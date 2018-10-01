<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Testify;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
class TestifyConstants
{
    public const BOOTSTRAP_CLASS_YVES = 'TESTIFY_CONSTANTS:BOOTSTRAP_CLASS_YVES';
    public const BOOTSTRAP_CLASS_ZED = 'TESTIFY_CONSTANTS:BOOTSTRAP_CLASS_ZED';

    /**
     * Specification:
     * - Host to be used for Presentation tests.
     * - When selenium server is installed on host, tests run in the hosts browser.
     */
    public const WEB_DRIVER_HOST = 'TESTIFY_CONSTANTS:WEB_DRIVER_HOST';
}

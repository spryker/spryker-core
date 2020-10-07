<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Testify\Helper;

use SprykerTest\Shared\Testify\Helper\Environment;

class YvesEnvironment extends Environment
{
    /**
     * @return void
     */
    public function _initialize(): void
    {
        defined('APPLICATION') || define('APPLICATION', 'YVES');

        parent::_initialize();
    }
}

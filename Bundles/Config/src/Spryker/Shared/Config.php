<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared;

use Spryker\Shared\Config\Config as SprykerConfig;

/**
 * @deprecated Use Spryker\Shared\Config\Config instead.
 */
class Config extends SprykerConfig
{
    /**
     * @return void
     */
    public static function getInstance()
    {
        parent::getInstance();
    }

    /**
     * @param string|null $environment
     *
     * @return void
     */
    public static function init($environment = null)
    {
        parent::init($environment);
    }
}

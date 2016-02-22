<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared;

use Spryker\Shared\Config\Config as SprykerConfig;

/**
 * @deprecated use Spryker\Shared\Config\Config instead
 */
class Config extends SprykerConfig
{

    /**
     * @param \ArrayObject|null $config
     *
     * @return void
     */
    public static function getInstance(\ArrayObject $config = null)
    {
        trigger_error('Deprecated, use Spryker\Shared\Config\Config instead', E_USER_DEPRECATED);

        parent::getInstance($config);
    }

    /**
     * @param null|string $environment
     *
     * @return void
     */
    public static function init($environment = null)
    {
        trigger_error('Deprecated, use Spryker\Shared\Config\Config instead', E_USER_DEPRECATED);

        parent::init($environment);
    }

}

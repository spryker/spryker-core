<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Communication\Plugin\Twig;

use Spryker\Shared\Twig\TwigFunction;
use Spryker\Zed\Application\ApplicationConfig;

abstract class AbstractApplicationTwigFunction extends TwigFunction
{
    /**
     * @return \Spryker\Zed\Application\ApplicationConfig
     */
    protected function getConfig(): ApplicationConfig
    {
        return new ApplicationConfig();
    }
}

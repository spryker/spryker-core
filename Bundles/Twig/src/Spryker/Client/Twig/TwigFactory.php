<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Twig;

use Spryker\Client\Kernel\AbstractFactory;

/**
 * @method \Spryker\Client\Twig\TwigConfig getConfig()
 */
class TwigFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Twig\TwigConfig
     */
    public function getModuleConfig(): TwigConfig
    {
        return $this->getConfig();
    }
}

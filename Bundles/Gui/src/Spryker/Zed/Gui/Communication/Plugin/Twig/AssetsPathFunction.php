<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Plugin\Twig;

use Spryker\Shared\Twig\TwigFunction;
use Spryker\Zed\Kernel\BundleConfigResolverAwareTrait;

/**
 * @method \Spryker\Zed\Gui\GuiConfig getConfig()
 */
class AssetsPathFunction extends TwigFunction
{
    use BundleConfigResolverAwareTrait;

    /**
     * @return string
     */
    protected function getFunctionName()
    {
        return 'assetsPath';
    }

    /**
     * @return callable
     */
    protected function getFunction()
    {
        return function ($path) {
            $path = ltrim($path, '/');

            return rtrim($this->getConfig()->getZedAssetsPath() . '/') . $path;
        };
    }
}

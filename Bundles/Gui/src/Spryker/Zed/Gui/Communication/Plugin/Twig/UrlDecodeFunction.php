<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Plugin\Twig;

use Spryker\Shared\Twig\TwigFunction;

/**
 * @deprecated Use `Spryker\Zed\Gui\Communication\Plugin\Twig\UrlDecodeTwigPlugin` instead.
 */
class UrlDecodeFunction extends TwigFunction
{
    /**
     * @return string
     */
    protected function getFunctionName(): string
    {
        return 'urldecode';
    }

    /**
     * @return callable
     */
    protected function getFunction(): callable
    {
        return function (string $url) {
            return urldecode($url);
        };
    }
}

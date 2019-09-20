<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Plugin\Twig;

use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Shared\Twig\TwigFunction;

/**
 * @deprecated Use `\Spryker\Zed\Gui\Communication\Plugin\Twig\UrlTwigPlugin` instead.
 */
class UrlFunction extends TwigFunction
{
    /**
     * @return string
     */
    protected function getFunctionName()
    {
        return 'url';
    }

    /**
     * @return callable
     */
    protected function getFunction()
    {
        return function ($url, array $query = [], array $options = []) {
            $url = Url::generate($url, $query, $options);
            $html = $url->buildEscaped();

            return $html;
        };
    }
}

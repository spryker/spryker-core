<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedUi\Communication\Plugin;

use Spryker\Service\Twig\Plugin\AbstractTwigExtensionPlugin;
use Spryker\Zed\ZedUi\Communication\Twig\NavigationComponentConfigFunction;

class ZedUiNavigationTwigPlugin extends AbstractTwigExtensionPlugin
{
    /**
     * @api
     *
     * @return \Spryker\Shared\Twig\TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new NavigationComponentConfigFunction(),
        ];
    }
}

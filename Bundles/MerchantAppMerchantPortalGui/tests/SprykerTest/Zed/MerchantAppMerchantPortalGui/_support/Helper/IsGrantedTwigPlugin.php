<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantAppMerchantPortalGui\Helper;

use Spryker\Zed\Twig\Communication\Plugin\AbstractTwigExtensionPlugin;
use Twig\TwigFunction;

class IsGrantedTwigPlugin extends AbstractTwigExtensionPlugin
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<\Twig\TwigFunction>
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('is_granted', function () {
                return true;
            }),
        ];
    }
}

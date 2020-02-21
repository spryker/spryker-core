<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedUi\Communication\Plugin;

use Spryker\Zed\Twig\Communication\Plugin\AbstractTwigExtensionPlugin;

/**
 * @method \Spryker\Zed\ZedUi\Communication\ZedUiCommunicationFactory getFactory()
 */
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
            $this->getFactory()->createNavigationComponentConfigFunction(),
        ];
    }
}

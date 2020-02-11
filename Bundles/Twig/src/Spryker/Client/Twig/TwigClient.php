<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Twig;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Twig\TwigFactory getFactory()
 */
class TwigClient extends AbstractClient implements TwigClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getYvesThemeName(): string
    {
        return $this->getFactory()
            ->getModuleConfig()
            ->getYvesThemeName();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getYvesThemeNameDefault(): string
    {
        return $this->getFactory()
            ->getModuleConfig()
            ->getYvesThemeNameDefault();
    }
}

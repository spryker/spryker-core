<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Twig;

/**
 * @method \Spryker\Client\Twig\TwigFactory getFactory()
 */
interface TwigClientInterface
{
    /**
     * Specification:
     * - Returns the current theme name for Yves.
     *
     * @api
     *
     * @return string
     */
    public function getYvesThemeName(): string;

    /**
     * Specification:
     * - Returns the default theme name for Yves.
     *
     * @api
     *
     * @return string
     */
    public function getYvesThemeNameDefault(): string;
}

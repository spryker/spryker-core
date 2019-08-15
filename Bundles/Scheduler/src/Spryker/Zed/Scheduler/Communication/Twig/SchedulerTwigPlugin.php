<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Communication\Twig;

use Spryker\Zed\Twig\Communication\Plugin\AbstractTwigExtensionPlugin;
use Twig\TwigFunction;

/**
 * @deprecated Use `Spryker\Zed\Scheduler\Communication\Plugin\Twig\SchedulerTwigPlugin` instead.
 */
class SchedulerTwigPlugin extends AbstractTwigExtensionPlugin
{
    public const EXTENSION_NAME = 'getenv';

    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction(static::EXTENSION_NAME, [$this, 'getEnvironmentVariableValueByName']),
        ];
    }

    /**
     * @param string $which
     *
     * @return string|false
     */
    public function getEnvironmentVariableValueByName(string $which)
    {
        return getenv($which);
    }
}

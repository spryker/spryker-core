<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig;

use Spryker\Shared\Kernel\AbstractSharedConfig;
use Spryker\Shared\Kernel\KernelConstants;

class TwigConfig extends AbstractSharedConfig
{
    protected const THEME_NAME_DEFAULT = 'default';

    /**
     * @return string
     */
    public function getYvesThemeName(): string
    {
        return $this->get(TwigConstants::YVES_THEME, '');
    }

    /**
     * @return string
     */
    public function getYvesThemeNameDefault(): string
    {
        return static::THEME_NAME_DEFAULT;
    }

    /**
     * @return array
     */
    public function getProjectNamespaces(): array
    {
        return $this->get(KernelConstants::PROJECT_NAMESPACES);
    }

    /**
     * @return array
     */
    public function getCoreNamespaces(): array
    {
        return $this->get(KernelConstants::CORE_NAMESPACES);
    }
}

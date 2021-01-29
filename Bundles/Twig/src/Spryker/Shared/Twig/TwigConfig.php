<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig;

use Spryker\Shared\Kernel\AbstractSharedConfig;
use Spryker\Shared\Kernel\KernelConstants;
use Twig\Cache\FilesystemCache;

class TwigConfig extends AbstractSharedConfig
{
    protected const THEME_NAME_DEFAULT = 'default';

    /**
     * @api
     *
     * @return string
     */
    public function getYvesThemeName(): string
    {
        return $this->get(TwigConstants::YVES_THEME, '');
    }

    /**
     * @api
     *
     * @return string
     */
    public function getYvesThemeNameDefault(): string
    {
        return static::THEME_NAME_DEFAULT;
    }

    /**
     * @api
     *
     * @return array
     */
    public function getProjectNamespaces(): array
    {
        return $this->get(KernelConstants::PROJECT_NAMESPACES);
    }

    /**
     * @api
     *
     * @return array
     */
    public function getCoreNamespaces(): array
    {
        return $this->get(KernelConstants::CORE_NAMESPACES);
    }

    /**
     * Specification:
     * - Defines the default path to the Twig `.pathCache` file.
     * - Can be redefined on Yves or Zed configs.
     *
     * @api
     *
     * @param string $application
     *
     * @return string
     */
    public function getDefaultPathCache($application = APPLICATION): string
    {
        return sprintf(
            '%s/src/Generated/%s/Twig/codeBucket/.pathCache',
            APPLICATION_ROOT_DIR,
            ucfirst(strtolower($application))
        );
    }

    /**
     * Specification:
     * - Defines the default twig options.
     *
     * @api
     *
     * @return array
     */
    public function getDefaultTwigOptions(): array
    {
        return [
            'cache' => new FilesystemCache(
                sprintf(
                    '%s/src/Generated/%s/Twig/codeBucket',
                    APPLICATION_ROOT_DIR,
                    ucfirst(strtolower(APPLICATION))
                ),
                FilesystemCache::FORCE_BYTECODE_INVALIDATION
            ),
        ];
    }
}

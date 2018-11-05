<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Twig;

use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Twig\TwigConstants;
use Spryker\Yves\Kernel\AbstractBundleConfig;

class TwigConfig extends AbstractBundleConfig
{
    /**
     * @return array
     */
    public function getTemplatePaths()
    {
        $paths = [];
        $paths = $this->addProjectTemplatePaths($paths);
        $paths = $this->addCoreTemplatePaths($paths);

        return $paths;
    }

    /**
     * @param array $paths
     *
     * @return array
     */
    protected function addProjectTemplatePaths(array $paths)
    {
        $namespaces = $this->get(KernelConstants::PROJECT_NAMESPACES);
        $storeName = $this->getStoreName();
        $themeName = $this->getThemeName();

        foreach ($namespaces as $namespace) {
            $paths[] = APPLICATION_SOURCE_DIR . '/' . $namespace . '/Yves/%s' . $storeName . '/Theme/' . $themeName;
            $paths[] = APPLICATION_SOURCE_DIR . '/' . $namespace . '/Yves/%s/Theme/' . $themeName;
            $paths[] = APPLICATION_SOURCE_DIR . '/' . $namespace . '/Shared/%s' . $storeName . '/Theme/' . $themeName;
            $paths[] = APPLICATION_SOURCE_DIR . '/' . $namespace . '/Shared/%s/Theme/' . $themeName;
        }

        return $paths;
    }

    /**
     * @param array $paths
     *
     * @return array
     */
    protected function addCoreTemplatePaths(array $paths)
    {
        $namespaces = $this->get(KernelConstants::CORE_NAMESPACES);
        $themeName = $this->getThemeName();

        foreach ($namespaces as $namespace) {
            $paths[] = APPLICATION_VENDOR_DIR . '/*/*/src/' . $namespace . '/Yves/%s/Theme/' . $themeName;
            $paths[] = APPLICATION_VENDOR_DIR . '/*/*/src/' . $namespace . '/Shared/%s/Theme/' . $themeName;
        }

        $paths[] = APPLICATION_VENDOR_DIR . '/spryker/*/src/Spryker/Yves/%s/Theme/' . $themeName;
        $paths[] = APPLICATION_VENDOR_DIR . '/spryker/*/src/Spryker/Shared/%s/Theme/' . $themeName;

        return $paths;
    }

    /**
     * @return string
     */
    protected function getThemeName()
    {
        return $this->get(TwigConstants::YVES_THEME);
    }

    /**
     * @return string
     */
    protected function getStoreName()
    {
        return Store::getInstance()->getStoreName();
    }

    /**
     * @return string
     */
    public function getCacheFilePath()
    {
        return $this->get(TwigConstants::YVES_PATH_CACHE_FILE, '');
    }

    /**
     * @return bool
     */
    public function isPathCacheEnabled()
    {
        return $this->get(TwigConstants::YVES_PATH_CACHE_ENABLED, true);
    }

    /**
     * @return int
     */
    public function getPermissionMode(): int
    {
        return $this->get(TwigConstants::DIRECTORY_PERMISSION, 0777);
    }
}

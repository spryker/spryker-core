<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Twig;

use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Twig\TwigConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class TwigConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getBundlesDirectory()
    {
        return $this->get(KernelConstants::SPRYKER_ROOT);
    }

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

        foreach ($namespaces as $namespace) {
            $paths[] = APPLICATION_SOURCE_DIR . '/' . $namespace . '/Zed/%s' . $storeName . '/Presentation/';
            $paths[] = APPLICATION_SOURCE_DIR . '/' . $namespace . '/Zed/%s/Presentation/';
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

        foreach ($namespaces as $namespace) {
            $paths[] = APPLICATION_VENDOR_DIR . '/*/*/src/' . $namespace . '/Zed/%s/Presentation/';
        }

        $paths[] = APPLICATION_VENDOR_DIR . '/spryker/*/src/Spryker/Zed/%s/Presentation/';

        return $paths;
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
        return $this->get(TwigConstants::ZED_PATH_CACHE_FILE, '');
    }

    /**
     * @return string
     */
    public function getCacheFilePathForYves()
    {
        return $this->get(TwigConstants::YVES_PATH_CACHE_FILE, '');
    }

    /**
     * @return bool
     */
    public function isPathCacheEnabled()
    {
        return $this->get(TwigConstants::ZED_PATH_CACHE_ENABLED, true);
    }

    /**
     * @return array
     */
    public function getZedDirectoryPathPatterns()
    {
        return $this->getZedDirectoryPathPattern();
    }

    /**
     * @deprecated Please use `getZedDirectoryPathPatterns()` instead.
     *
     * @return array
     */
    public function getZedDirectoryPathPattern()
    {
        $directories = array_merge(
            glob('vendor/*/*/src/*/Zed/*/Presentation'),
            glob('src/*/Zed/*/Presentation')
        );

        return $directories;
    }

    /**
     * @return array
     */
    public function getYvesDirectoryPathPatterns()
    {
        return $this->getYvesDirectoryPathPattern();
    }

    /**
     * @deprecated Please use `getYvesDirectoryPathPatterns()` instead.
     *
     * @return array
     */
    public function getYvesDirectoryPathPattern()
    {
        $currentThemeName = $this->get(TwigConstants::YVES_THEME);
        $directories = array_merge(
            glob('vendor/*/*/src/*/Yves/*/Theme/' . $currentThemeName),
            glob('src/*/Yves/*/Theme/' . $currentThemeName)
        );

        return $directories;
    }

    /**
     * @return int
     */
    public function getPermissionMode(): int
    {
        return $this->get(TwigConstants::DIRECTORY_PERMISSION, 0777);
    }
}

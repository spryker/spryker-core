<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Twig;

use ReflectionClass;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Twig\TwigConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;
use Symfony\Bridge\Twig\Extension\FormExtension;

/**
 * @method \Spryker\Shared\Twig\TwigConfig getSharedConfig()
 */
class TwigConfig extends AbstractBundleConfig
{
    protected const THEME_DEFAULT = 'default';

    /**
     * @return string
     */
    public function getBundlesDirectory()
    {
        return $this->get(KernelConstants::SPRYKER_ROOT);
    }

    /**
     * @return string[]
     */
    public function getTemplatePaths()
    {
        $paths = [];
        $paths = $this->addProjectTemplatePaths($paths);
        $paths = $this->addCoreTemplatePaths($paths);

        return $paths;
    }

    /**
     * @param string[] $paths
     *
     * @return string[]
     */
    protected function addProjectTemplatePaths(array $paths)
    {
        $namespaces = $this->getProjectNamespaces();
        $storeName = $this->getStoreName();

        $themeName = $this->getThemeName();
        $themeNameDefault = $this->getThemeNameDefault();

        foreach ($namespaces as $namespace) {
            if ($this->getIsThemeDefined($themeName, $themeNameDefault)) {
                $paths[] = APPLICATION_SOURCE_DIR . '/' . $namespace . '/Zed/%s' . $storeName . '/Presentation/Theme/' . $themeName;
                $paths[] = APPLICATION_SOURCE_DIR . '/' . $namespace . '/Zed/%s/Presentation/Theme/' . $themeName;
            }

            $paths[] = APPLICATION_SOURCE_DIR . '/' . $namespace . '/Zed/%s' . $storeName . '/Presentation/Theme/' . $themeNameDefault;
            $paths[] = APPLICATION_SOURCE_DIR . '/' . $namespace . '/Zed/%s/Presentation/Theme/' . $themeNameDefault;
            $paths[] = APPLICATION_SOURCE_DIR . '/' . $namespace . '/Zed/%s' . $storeName . '/Presentation/';
            $paths[] = APPLICATION_SOURCE_DIR . '/' . $namespace . '/Zed/%s/Presentation/';
        }

        return $paths;
    }

    /**
     * @param string[] $paths
     *
     * @return string[]
     */
    protected function addCoreTemplatePaths(array $paths)
    {
        $namespaces = $this->getCoreNamespaces();
        $themeName = $this->getThemeName();
        $themeNameDefault = $this->getThemeNameDefault();
        $isThemeDefined = $this->getIsThemeDefined($themeName, $themeNameDefault);

        foreach ($namespaces as $namespace) {
            if ($isThemeDefined) {
                $paths[] = APPLICATION_VENDOR_DIR . '/*/*/src/' . $namespace . '/Zed/%s/Presentation/Theme/' . $themeName;
            }

            $paths[] = APPLICATION_VENDOR_DIR . '/*/*/src/' . $namespace . '/Zed/%s/Presentation/Theme/' . $themeNameDefault;
            $paths[] = APPLICATION_VENDOR_DIR . '/*/*/src/' . $namespace . '/Zed/%s/Presentation/';
        }

        if ($isThemeDefined) {
            $paths[] = APPLICATION_VENDOR_DIR . '/spryker/*/src/Spryker/Zed/%s/Presentation/Theme/' . $themeName;
        }

        $paths[] = APPLICATION_VENDOR_DIR . '/spryker/*/src/Spryker/Zed/%s/Presentation/Theme/' . $themeNameDefault;
        $paths[] = APPLICATION_VENDOR_DIR . '/spryker/*/src/Spryker/Zed/%s/Presentation/';

        return $paths;
    }

    /**
     * @return string[]
     */
    public function getProjectNamespaces(): array
    {
        return $this->getSharedConfig()->getProjectNamespaces();
    }

    /**
     * @return string[]
     */
    public function getCoreNamespaces(): array
    {
        return $this->getSharedConfig()->getCoreNamespaces();
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
     * @return string[]
     */
    public function getZedDirectoryPathPatterns()
    {
        return $this->getZedDirectoryPathPattern();
    }

    /**
     * @deprecated Please use `getZedDirectoryPathPatterns()` instead.
     *
     * @return string[]
     */
    public function getZedDirectoryPathPattern()
    {
        $themeName = $this->getThemeName();
        $themeNameDefault = $this->getThemeNameDefault();

        if (!$this->getIsThemeDefined($themeName, $themeNameDefault)) {
            $themeName = $themeNameDefault;
        }

        $directories = array_merge(
            glob('vendor/*/*/src/*/Zed/*/Presentation', GLOB_ONLYDIR | GLOB_NOSORT),
            glob('vendor/*/*/src/*/Zed/*/Presentation/Theme/' . $themeNameDefault, GLOB_ONLYDIR | GLOB_NOSORT),
            glob('vendor/*/*/src/*/Zed/*/Presentation/Theme/' . $themeName, GLOB_ONLYDIR | GLOB_NOSORT),
            glob('src/*/Zed/*/Presentation', GLOB_ONLYDIR | GLOB_NOSORT),
            glob('src/*/Zed/*/Presentation/Theme/' . $themeNameDefault, GLOB_ONLYDIR | GLOB_NOSORT),
            glob('src/*/Zed/*/Presentation/Theme/' . $themeName, GLOB_ONLYDIR | GLOB_NOSORT)
        );

        return $directories;
    }

    /**
     * @return string[]
     */
    public function getYvesDirectoryPathPatterns()
    {
        return $this->getYvesDirectoryPathPattern();
    }

    /**
     * @deprecated Please use `getYvesDirectoryPathPatterns()` instead.
     *
     * @return string[]
     */
    public function getYvesDirectoryPathPattern()
    {
        $themeName = $this->getSharedConfig()->getYvesThemeName();
        $themeNameDefault = $this->getSharedConfig()->getYvesThemeNameDefault();

        if ($themeName === '') {
            $themeName = $themeNameDefault;
        }

        $directories = array_merge(
            glob('vendor/*/*/src/*/Yves/*/Theme/' . $themeName, GLOB_ONLYDIR | GLOB_NOSORT),
            glob('src/*/Yves/*/Theme/' . $themeNameDefault, GLOB_ONLYDIR | GLOB_NOSORT),
            glob('src/*/Yves/*/Theme/' . $themeName, GLOB_ONLYDIR | GLOB_NOSORT)
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

    /**
     * @return array
     */
    public function getTwigOptions(): array
    {
        return $this->get(TwigConstants::ZED_TWIG_OPTIONS, []);
    }

    /**
     * @return string[]
     */
    public function getFormTemplateDirectories(): array
    {
        $reflectedFormExtension = new ReflectionClass(FormExtension::class);

        return [
            dirname($reflectedFormExtension->getFileName()) . '/../Resources/views/Form',
        ];
    }

    /**
     * @return string
     */
    public function getThemeName(): string
    {
        return $this->get(TwigConstants::ZED_THEME, '');
    }

    /**
     * @return string
     */
    public function getThemeNameDefault(): string
    {
        return static::THEME_DEFAULT;
    }

    /**
     * @param string $themeName
     * @param string $themeNameDefault
     *
     * @return bool
     */
    protected function getIsThemeDefined(string $themeName, string $themeNameDefault): bool
    {
        return $themeName !== '' && $themeName !== $themeNameDefault;
    }
}

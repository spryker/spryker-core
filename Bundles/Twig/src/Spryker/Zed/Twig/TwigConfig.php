<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Twig;

use ReflectionClass;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Shared\Twig\TwigConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;
use Symfony\Bridge\Twig\Extension\FormExtension;

/**
 * @method \Spryker\Shared\Twig\TwigConfig getSharedConfig()
 */
class TwigConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return string
     */
    public function getBundlesDirectory()
    {
        return $this->get(KernelConstants::SPRYKER_ROOT);
    }

    /**
     * @api
     *
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

        foreach ($namespaces as $namespace) {
            $paths[] = APPLICATION_SOURCE_DIR . '/' . $namespace . '/Zed/%s' . APPLICATION_CODE_BUCKET . '/Presentation/';
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

        foreach ($namespaces as $namespace) {
            $paths[] = APPLICATION_VENDOR_DIR . '/*/*/src/' . $namespace . '/Zed/%s/Presentation/';
        }

        $paths[] = APPLICATION_VENDOR_DIR . '/spryker/*/src/Spryker/Zed/%s/Presentation/';

        return $paths;
    }

    /**
     * @api
     *
     * @return string[]
     */
    public function getProjectNamespaces(): array
    {
        return $this->getSharedConfig()->getProjectNamespaces();
    }

    /**
     * @api
     *
     * @return string[]
     */
    public function getCoreNamespaces(): array
    {
        return $this->getSharedConfig()->getCoreNamespaces();
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCacheFilePath()
    {
        return $this->get(TwigConstants::ZED_PATH_CACHE_FILE, $this->getSharedConfig()->getDefaultPathCache());
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCacheFilePathForYves()
    {
        return $this->get(TwigConstants::YVES_PATH_CACHE_FILE, $this->getSharedConfig()->getDefaultPathCache());
    }

    /**
     * @api
     *
     * @return bool
     */
    public function isPathCacheEnabled()
    {
        return $this->get(TwigConstants::ZED_PATH_CACHE_ENABLED, true);
    }

    /**
     * @api
     *
     * @return string[]
     */
    public function getZedDirectoryPathPatterns()
    {
        return $this->getZedDirectoryPathPattern();
    }

    /**
     * @api
     *
     * @deprecated Use {@link getZedDirectoryPathPatterns()} instead.
     *
     * @return string[]
     */
    public function getZedDirectoryPathPattern()
    {
        $directories = array_merge(
            glob('vendor/*/*/src/*/Zed/*/Presentation', GLOB_ONLYDIR | GLOB_NOSORT),
            glob('src/*/Zed/*/Presentation', GLOB_ONLYDIR | GLOB_NOSORT)
        );

        return $directories;
    }

    /**
     * @api
     *
     * @return string[]
     */
    public function getYvesDirectoryPathPatterns()
    {
        return $this->getYvesDirectoryPathPattern();
    }

    /**
     * @api
     *
     * @deprecated Use {@link getYvesDirectoryPathPatterns()} instead.
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
     * @api
     *
     * @return int
     */
    public function getPermissionMode(): int
    {
        return $this->get(TwigConstants::DIRECTORY_PERMISSION, 0777);
    }

    /**
     * @api
     *
     * @return array
     */
    public function getTwigOptions(): array
    {
        return array_replace(
            $this->getSharedConfig()->getDefaultTwigOptions(),
            $this->get(TwigConstants::ZED_TWIG_OPTIONS, [])
        );
    }

    /**
     * @api
     *
     * @return string[]
     */
    public function getFormTemplateDirectories(): array
    {
        $reflectedFormExtension = new ReflectionClass(FormExtension::class);

        return [
            dirname($reflectedFormExtension->getFileName()) . '/../Resources/views/Form',
        ];
    }
}

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
     * @var string
     */
    protected const APPLICATION_ZED = 'ZED';

    /**
     * @uses \Spryker\Yves\Twig\TwigConfig::APPLICATION_YVES
     *
     * @var string
     */
    protected const APPLICATION_YVES = 'YVES';

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
     * @return array<string>
     */
    public function getTemplatePaths()
    {
        $paths = [];
        $paths = $this->addProjectTemplatePaths($paths);
        $paths = $this->addCoreTemplatePaths($paths);

        return $paths;
    }

    /**
     * @param array<string> $paths
     *
     * @return array<string>
     */
    protected function addProjectTemplatePaths(array $paths)
    {
        $namespaces = $this->getProjectNamespaces();

        foreach ($namespaces as $namespace) {
            $paths[] = rtrim(APPLICATION_SOURCE_DIR, '/') . '/' . $namespace . '/Zed/%s' . APPLICATION_CODE_BUCKET . '/Presentation/';
            $paths[] = rtrim(APPLICATION_SOURCE_DIR, '/') . '/' . $namespace . '/Zed/%s/Presentation/';
        }

        return $paths;
    }

    /**
     * @param array<string> $paths
     *
     * @return array<string>
     */
    protected function addCoreTemplatePaths(array $paths)
    {
        $namespaces = $this->getCoreNamespaces();

        foreach ($namespaces as $namespace) {
            $paths[] = rtrim(APPLICATION_VENDOR_DIR, '/') . '/*/*/src/' . $namespace . '/Zed/%s/Presentation/';
        }

        $paths[] = rtrim(APPLICATION_VENDOR_DIR, '/') . '/spryker/*/src/Spryker/Zed/%s/Presentation/';

        return $paths;
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getProjectNamespaces(): array
    {
        return $this->getSharedConfig()->getProjectNamespaces();
    }

    /**
     * @api
     *
     * @return array<string>
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
        return $this->get(TwigConstants::ZED_PATH_CACHE_FILE, $this->getSharedConfig()->getDefaultPathCache(static::APPLICATION_ZED));
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCacheFilePathForYves()
    {
        return $this->get(TwigConstants::YVES_PATH_CACHE_FILE, $this->getSharedConfig()->getDefaultPathCache(static::APPLICATION_YVES));
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
     * @return array<string>
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
     * @return array<string>
     */
    public function getZedDirectoryPathPattern()
    {
        /** @var array<int, string> $vendorPresentations */
        $vendorPresentations = glob('vendor/*/*/src/*/Zed/*/Presentation', GLOB_ONLYDIR | GLOB_NOSORT);
        /** @var array<int, string> $projectPresentations */
        $projectPresentations = glob('src/*/Zed/*/Presentation', GLOB_ONLYDIR | GLOB_NOSORT);

        $directories = array_merge(
            $vendorPresentations,
            $projectPresentations,
        );

        return $directories;
    }

    /**
     * @api
     *
     * @return array<string>
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
     * @return array<string>
     */
    public function getYvesDirectoryPathPattern()
    {
        $themeName = $this->getSharedConfig()->getYvesThemeName();
        $themeNameDefault = $this->getSharedConfig()->getYvesThemeNameDefault();

        if ($themeName === '') {
            $themeName = $themeNameDefault;
        }

        /** @var array<int, string> $vendorThemeNames */
        $vendorThemeNames = glob('vendor/*/*/src/*/Yves/*/Theme/' . $themeName, GLOB_ONLYDIR | GLOB_NOSORT);
        /** @var array<int, string> $projectDefaultThemeNames */
        $projectDefaultThemeNames = glob('src/*/Yves/*/Theme/' . $themeNameDefault, GLOB_ONLYDIR | GLOB_NOSORT);
        /** @var array<int, string> $projectThemeNames */
        $projectThemeNames = glob('src/*/Yves/*/Theme/' . $themeName, GLOB_ONLYDIR | GLOB_NOSORT);

        $directories = array_merge(
            $vendorThemeNames,
            $projectDefaultThemeNames,
            $projectThemeNames,
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
     * @return array<string, mixed>
     */
    public function getTwigOptions(): array
    {
        return array_replace(
            $this->getSharedConfig()->getDefaultTwigOptions(),
            $this->get(TwigConstants::ZED_TWIG_OPTIONS, []),
        );
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getFormTemplateDirectories(): array
    {
        $reflectedFormExtension = new ReflectionClass(FormExtension::class);
        /** @var string $fileName */
        $fileName = $reflectedFormExtension->getFileName();

        return [
            dirname($fileName) . '/../Resources/views/Form',
        ];
    }
}

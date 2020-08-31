<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Twig;

use ReflectionClass;
use Spryker\Shared\Twig\TwigConstants;
use Spryker\Yves\Kernel\AbstractBundleConfig;
use Symfony\Bridge\Twig\Extension\FormExtension;

/**
 * @method \Spryker\Shared\Twig\TwigConfig getSharedConfig()
 */
class TwigConfig extends AbstractBundleConfig
{
    protected const APPLICATION_YVES = 'YVES';
    
    /**
     * @api
     *
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
        $namespaces = $this->getProjectNamespaces();

        $themeName = $this->getThemeName();
        $themeNameDefault = $this->getThemeNameDefault();

        foreach ($namespaces as $namespace) {
            if ($themeName !== '' && $themeName !== $themeNameDefault) {
                $paths[] = rtrim(APPLICATION_SOURCE_DIR, DIRECTORY_SEPARATOR) . '/' . $namespace . '/Yves/%s' . APPLICATION_CODE_BUCKET . '/Theme/' . $themeName;
                $paths[] = rtrim(APPLICATION_SOURCE_DIR, DIRECTORY_SEPARATOR) . '/' . $namespace . '/Yves/%s/Theme/' . $themeName;
                $paths[] = rtrim(APPLICATION_SOURCE_DIR, DIRECTORY_SEPARATOR) . '/' . $namespace . '/Shared/%s' . APPLICATION_CODE_BUCKET . '/Theme/' . $themeName;
                $paths[] = rtrim(APPLICATION_SOURCE_DIR, DIRECTORY_SEPARATOR) . '/' . $namespace . '/Shared/%s/Theme/' . $themeName;
            }

            $paths[] = rtrim(APPLICATION_SOURCE_DIR, DIRECTORY_SEPARATOR) . '/' . $namespace . '/Yves/%s' . APPLICATION_CODE_BUCKET . '/Theme/' . $themeNameDefault;
            $paths[] = rtrim(APPLICATION_SOURCE_DIR, DIRECTORY_SEPARATOR) . '/' . $namespace . '/Yves/%s/Theme/' . $themeNameDefault;
            $paths[] = rtrim(APPLICATION_SOURCE_DIR, DIRECTORY_SEPARATOR) . '/' . $namespace . '/Shared/%s' . APPLICATION_CODE_BUCKET . '/Theme/' . $themeNameDefault;
            $paths[] = rtrim(APPLICATION_SOURCE_DIR, DIRECTORY_SEPARATOR) . '/' . $namespace . '/Shared/%s/Theme/' . $themeNameDefault;
        }

        return $paths;
    }

    /**
     * @api
     *
     * @return array
     */
    public function getProjectNamespaces(): array
    {
        return $this->getSharedConfig()->getProjectNamespaces();
    }

    /**
     * @api
     *
     * @return array
     */
    public function getCoreNamespaces(): array
    {
        return $this->getSharedConfig()->getCoreNamespaces();
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
            $paths[] = rtrim(APPLICATION_VENDOR_DIR, DIRECTORY_SEPARATOR) . '/*/*/src/' . $namespace . '/Yves/%s/Theme/' . $this->getThemeNameDefault();
            $paths[] = rtrim(APPLICATION_VENDOR_DIR, DIRECTORY_SEPARATOR) . '/*/*/src/' . $namespace . '/Shared/%s/Theme/' . $this->getThemeNameDefault();
        }

        return $paths;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getThemeName(): string
    {
        return $this->getSharedConfig()->getYvesThemeName();
    }

    /**
     * @api
     *
     * @return string
     */
    public function getThemeNameDefault(): string
    {
        return $this->getSharedConfig()->getYvesThemeNameDefault();
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCacheFilePath()
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
        return $this->get(TwigConstants::YVES_PATH_CACHE_ENABLED, true);
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
            $this->get(TwigConstants::YVES_TWIG_OPTIONS, [])
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

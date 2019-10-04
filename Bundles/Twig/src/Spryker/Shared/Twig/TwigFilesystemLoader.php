<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig;

use Spryker\Shared\Twig\Cache\CacheInterface;
use Spryker\Shared\Twig\Loader\FilesystemLoaderInterface;
use Spryker\Shared\Twig\TemplateNameExtractor\TemplateNameExtractorInterface;
use Twig\Error\LoaderError;

class TwigFilesystemLoader implements FilesystemLoaderInterface
{
    /**
     * @var array
     */
    protected $paths;

    /**
     * @var \Spryker\Shared\Twig\Cache\CacheInterface
     */
    protected $cache;

    /**
     * @var \Spryker\Shared\Twig\TemplateNameExtractor\TemplateNameExtractorInterface
     */
    protected $templateNameExtractor;

    /**
     * @param array $paths
     * @param \Spryker\Shared\Twig\Cache\CacheInterface $cache
     * @param \Spryker\Shared\Twig\TemplateNameExtractor\TemplateNameExtractorInterface $templateNameExtractor
     */
    public function __construct(array $paths, CacheInterface $cache, TemplateNameExtractorInterface $templateNameExtractor)
    {
        $this->paths = $paths;
        $this->cache = $cache;
        $this->templateNameExtractor = $templateNameExtractor;
    }

    /**
     * @param string $path
     * @param string $namespace
     *
     * @return $this
     */
    public function addPath($path, $namespace = '__main__')
    {
        $this->paths[] = rtrim($path, '/\\');

        return $this;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function getSource($name)
    {
        return file_get_contents($this->findTemplate($name));
    }

    /**
     * @param string $name
     * @param int $time
     *
     * @return bool
     */
    public function isFresh($name, $time)
    {
        return filemtime($this->findTemplate($name)) <= $time;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function getCacheKey($name)
    {
        return $this->findTemplate($name);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function findTemplate($name)
    {
        if ($this->cache->has($name)) {
            return $this->returnFromCache($name);
        }

        $this->validateName($name);

        $bundle = $this->templateNameExtractor->extractBundleName($name);
        $templatePath = $this->templateNameExtractor->extractTemplatePath($name);

        return $this->load($name, $bundle, $templatePath);
    }

    /**
     * @param string $moduleOrganization
     *
     * @return array
     */
    protected function getPathsForBundle($moduleOrganization)
    {
        $paths = [];

        $organization = $this->extractOrganization($moduleOrganization);
        $module = $this->extractModule($moduleOrganization);

        foreach ($this->paths as $path) {
            $package = $module;
            $path = $this->getNamespacedPath($path, $organization);

            if ($this->isPathInSplit($path)) {
                $package = $this->filterBundleName($module);
            }

            $path = sprintf($path, $module, $package);
            if (strpos($path, '*') === false && is_dir($path)) {
                $paths[] = $path;

                continue;
            }

            $path = glob($path, GLOB_ONLYDIR | GLOB_NOSORT);
            if (count($path) > 0) {
                $paths[] = $path[0];
            }
        }

        return $paths;
    }

    /**
     * @param string $organizationModule
     *
     * @return string|null
     */
    protected function extractOrganization(string $organizationModule): ?string
    {
        if (strpos($organizationModule, ':') === false) {
            return null;
        }

        $organizationModule = explode(':', $organizationModule);

        return current($organizationModule);
    }

    /**
     * @param string $organizationModule
     *
     * @return string|null
     */
    protected function extractModule(string $organizationModule): ?string
    {
        if (strpos($organizationModule, ':') === false) {
            return $organizationModule;
        }

        $organizationModule = explode(':', $organizationModule);

        return array_pop($organizationModule);
    }

    /**
     * @param string $path
     * @param string|null $organization
     *
     * @return string
     */
    protected function getNamespacedPath(string $path, ?string $organization): string
    {
        if ($organization === null) {
            return $path;
        }

        $pathFragments = explode(DIRECTORY_SEPARATOR, $path);
        $positionOfSourceDirectory = array_search('src', $pathFragments);
        $pathFragments[$positionOfSourceDirectory + 1] = $organization;

        return implode(DIRECTORY_SEPARATOR, $pathFragments);
    }

    /**
     * @param string $name
     * @param string $bundle
     * @param string $templateName
     *
     * @throws \Twig\Error\LoaderError
     *
     * @return string
     */
    protected function load($name, $bundle, $templateName)
    {
        $paths = $this->getPathsForBundle($bundle);
        foreach ($paths as $path) {
            if (is_file($path . '/' . $templateName)) {
                $fullFilePath = $path . '/' . $templateName;
                $this->cache->set($name, $fullFilePath);

                return $fullFilePath;
            }
        }

        $this->cache->set($name, false);

        throw new LoaderError(sprintf('Unable to find template "%s" (looked into: %s).', $templateName, implode(', ', $paths)));
    }

    /**
     * @param string $name
     *
     * @throws \Twig\Error\LoaderError
     *
     * @return string
     */
    protected function returnFromCache($name)
    {
        $template = $this->cache->get($name);
        if (!$template) {
            throw new LoaderError(sprintf('Unable to find template "%s" (cached).', $name));
        }

        return $template;
    }

    /**
     * @param string $name
     *
     * @throws \Twig\Error\LoaderError
     *
     * @return void
     */
    protected function validateName($name)
    {
        $nameWithoutPrefix = ltrim($name, '@/');
        $firstSeparatorPosition = strpos($nameWithoutPrefix, '/');

        if ($firstSeparatorPosition === false) {
            $this->cache->set($name, false);

            throw new LoaderError(sprintf('Malformed bundle template name "%s" (expecting "@Bundle/template_name").', $name));
        }
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    protected function isPathInSplit($path)
    {
        return preg_match('/vendor\/spryker\/[a-zA-Z0-9._-]+\/Bundles/', $path) === 0 && strpos($path, 'vendor/') > 0;
    }

    /**
     * @param string $bundleName
     *
     * @return string
     */
    protected function filterBundleName($bundleName)
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1' . addcslashes('-', '$') . '$2', $bundleName));
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig;

use Spryker\Shared\Twig\Cache\CacheInterface;
use Spryker\Shared\Twig\TemplateNameExtractor\TemplateNameExtractorInterface;
use Twig_Error_Loader;
use Twig_LoaderInterface;

class TwigFilesystemLoader implements Twig_LoaderInterface
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
     *
     * @return $this
     */
    public function addPath($path)
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
     * {@inheritdoc}
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
     * @param string $bundle
     *
     * @return array
     */
    protected function getPathsForBundle($bundle)
    {
        $paths = [];
        foreach ($this->paths as $path) {
            $package = $bundle;

            if ($this->isPathInSplit($path)) {
                $package = $this->filterBundleName($bundle);
            }

            $path = sprintf($path, $bundle, $package);
            if (strpos($path, '*') === false) {
                $paths[] = $path;

                continue;
            }

            $path = glob($path);
            if (count($path) > 0) {
                $paths[] = $path[0];
            }
        }

        return $paths;
    }

    /**
     * @param string $name
     * @param string $bundle
     * @param string $templateName
     *
     * @throws \Twig_Error_Loader
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

        throw new Twig_Error_Loader(sprintf('Unable to find template "%s" (looked into: %s).', $templateName, implode(', ', $paths)));
    }

    /**
     * @param string $name
     *
     * @throws \Twig_Error_Loader
     *
     * @return string
     */
    protected function returnFromCache($name)
    {
        if ($this->cache->get($name) === false) {
            throw new Twig_Error_Loader(sprintf('Unable to find template "%s" (cached).', $name));
        }

        return $this->cache->get($name);
    }

    /**
     * @param string $name
     *
     * @throws \Twig_Error_Loader
     *
     * @return void
     */
    protected function validateName($name)
    {
        $nameWithoutPrefix = ltrim($name, '@/');
        $firstSeparatorPosition = strpos($nameWithoutPrefix, '/');

        if ($firstSeparatorPosition === false) {
            $this->cache->set($name, false);

            throw new Twig_Error_Loader(sprintf('Malformed bundle template name "%s" (expecting "@Bundle/template_name").', $name));
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

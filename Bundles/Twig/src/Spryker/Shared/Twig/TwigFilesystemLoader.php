<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig;

use Spryker\Shared\Twig\Cache\CacheInterface;
use Spryker\Shared\Twig\Dependency\Service\TwigToUtilTextServiceInterface;
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
     * @var \Spryker\Shared\Twig\Dependency\Service\TwigToUtilTextServiceInterface
     */
    protected $utilTextService;

    /**
     * @param array $paths
     * @param \Spryker\Shared\Twig\Cache\CacheInterface $cache
     * @param \Spryker\Shared\Twig\Dependency\Service\TwigToUtilTextServiceInterface $utilTextService
     */
    public function __construct(array $paths, CacheInterface $cache, TwigToUtilTextServiceInterface $utilTextService)
    {
        $this->paths = $paths;
        $this->cache = $cache;
        $this->utilTextService = $utilTextService;
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
     *
     */
    protected function findTemplate($name)
    {
        $name = $this->normalizeName($name);

        if ($this->cache->has($name)) {
            return $this->returnFromCache($name);
        }

        $nameWithoutPrefix = ltrim($name, '@/');
        $pos = strpos($nameWithoutPrefix, '/');

        $this->validateName($name, $pos);

        $bundle = ucfirst(substr($nameWithoutPrefix, 0, $pos));
        $templateName = substr($nameWithoutPrefix, $pos + 1);

        return $this->load($name, $bundle, $templateName);
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
                $package = $this->utilTextService->camelCaseToDash($bundle);
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
     * @return array
     */
    protected function normalizeName($name)
    {
        $name = (string)$name;
        $name = str_replace(['///', '//', '\\'], '/', $name);

        $nameParts = explode('/', $name);
        $templateName = array_pop($nameParts);
        $templateName = $this->utilTextService->camelCaseToDash($templateName);
        array_push($nameParts, $templateName);
        $name = implode('/', $nameParts);

        return $name;
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
     * @param bool|int $pos
     *
     * @throws \Twig_Error_Loader
     *
     * @return void
     */
    protected function validateName($name, $pos)
    {
        if ($pos === false) {
            $this->cache->set($name, false);

            throw new Twig_Error_Loader(sprintf('Malformed bundle template name "%s" (expecting "@bundle/template_name").', $name));
        }
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    protected function isPathInSplit($path)
    {
        return strpos($path, 'vendor/spryker/spryker/Bundles') === false && strpos($path, 'vendor/') > 0;
    }

}

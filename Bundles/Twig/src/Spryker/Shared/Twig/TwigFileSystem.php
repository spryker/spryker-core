<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig;

use Twig\Error\LoaderError;
use Twig\Loader\FilesystemLoader;
use Zend\Filter\Word\CamelCaseToDash;

/**
 * @deprecated Use TwigFilesystemLoader instead.
 * @codeCoverageIgnore
 */
class TwigFileSystem extends FilesystemLoader
{
    /**
     * @param array $paths
     * @param string $namespace
     *
     * @return void
     */
    public function setPaths($paths, $namespace = self::MAIN_NAMESPACE)
    {
        $this->paths = [];
        foreach ($paths as $path) {
            $this->addPath($path);
        }
    }

    /**
     * @param string $path
     * @param string $namespace
     *
     * @return void
     */
    public function addPath($path, $namespace = self::MAIN_NAMESPACE)
    {
        // invalidate the cache
        $this->cache = [];
        $this->paths[] = rtrim($path, '/\\');
    }

    /**
     * @param string $path
     * @param string $namespace
     *
     * @return void
     */
    public function prependPath($path, $namespace = self::MAIN_NAMESPACE)
    {
        // invalidate the cache
        $this->cache = [];

        $path = rtrim($path, '/\\');

        if (empty($this->paths)) {
            $this->paths[] = $path;
        } else {
            array_unshift($this->paths, $path);
        }
    }

    /**
     * @param string $bundle
     *
     * @return array
     */
    protected function getPathsForBundle($bundle)
    {
        $paths = [];
        $filter = new CamelCaseToDash();
        foreach ($this->paths as $path) {
            $formattedBundleName = $bundle;
            if (preg_match('/vendor\/spryker\/[a-zA-Z0-9._-]+\/Bundles/', $path) === 0 && strpos($path, 'vendor/spryker/') > 0) {
                $formattedBundleName = strtolower($filter->filter($bundle));
            }
            $path = sprintf($path, $bundle, $formattedBundleName);
            if (strpos($path, '*') !== false) {
                $path = glob($path);
                if (count($path) > 0) {
                    $paths[] = $path[0];
                }
            } else {
                $paths[] = $path;
            }
        }

        return $paths;
    }

    /**
     * {@inheritdoc}
     *
     * @param string $name
     *
     * @throws \Twig\Error\LoaderError
     *
     * @return string|false|null The template name or false/null
     */
    protected function findTemplate($name)
    {
        $name = (string)$name;

        // normalize name
        $name = str_replace(['///', '//', '\\'], '/', $name);

        $nameParts = explode('/', $name);
        $templateName = array_pop($nameParts);
        $filter = new CamelCaseToDash();
        $templateName = strtolower($filter->filter($templateName));
        array_push($nameParts, $templateName);
        $name = implode('/', $nameParts);

        if (isset($this->cache[$name])) {
            if ($this->cache[$name] !== false) {
                return $this->cache[$name];
            } else {
                throw new LoaderError(sprintf('Unable to find template "%s" (cached).', $name));
            }
        }

        $this->validateName($name);

        if (isset($name[0]) && $name[0] === '@') {
            $pos = strpos($name, '/');
            if ($pos === false) {
                $this->cache[$name] = false;
                throw new LoaderError(sprintf('Malformed bundle template name "%s" (expecting "@bundle/template_name").', $name));
            }
            $bundle = ucfirst(substr($name, 1, $pos - 1));
            $templateName = ucfirst(substr($name, $pos + 1));

            return $this->load($name, $bundle, $templateName);
        }

        $name = '/' . ltrim($name, '/');
        $pos = strpos(ltrim($name, '/'), '/');
        if ($pos === false) {
            $this->cache[$name] = false;
            throw new LoaderError(sprintf('Malformed bundle template name "%s" (expecting "@bundle/template_name").', $name));
        }
        $bundle = ucfirst(substr($name, 1, $pos));
        $templateName = ucfirst(substr($name, $pos + 2));

        return $this->load($name, $bundle, $templateName);
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
                return $this->cache[$name] = $path . '/' . $templateName;
            }
        }

        $this->cache[$name] = false;
        throw new LoaderError(sprintf('Unable to find template "%s" (looked into: %s).', $templateName, implode(', ', $paths)));
    }
}

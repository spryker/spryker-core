<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Twig\Model\Loader;

use Twig_Error_Loader;
use Twig_LoaderInterface;

class FilesystemLoader implements Twig_LoaderInterface
{

    /**
     * @var array
     */
    protected $paths;

    /**
     * @var array
     */
    protected $cache;

    /**
     * @param array $paths
     */
    public function __construct(array $paths = [])
    {
        $this->paths = $paths;
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
    public function getSource($name)
    {
        return file_get_contents($this->findTemplate($name));
    }

    /**
     * {@inheritdoc}
     */
    public function isFresh($name, $time)
    {
        return filemtime($this->findTemplate($name)) <= $time;
    }

    /**
     * @param string $name
     *
     * @throws \Twig_Error_Loader
     *
     * @return string
     */
    protected function findTemplate($name)
    {
        $name = (string)$name;

        if (isset($this->cache[$name])) {
            if ($this->cache[$name] !== false) {
                return $this->cache[$name];
            } else {
                throw new Twig_Error_Loader(sprintf('Unable to find template "%s" (cached).', $name));
            }
        }

        // normalize name
        $name = str_replace(['///', '//', '\\'], '/', $name);

        if (isset($name[0]) && $name[0] === '@') {
            $pos = strpos($name, '/');
            if ($pos === false) {
                $this->cache[$name] = false;
                throw new Twig_Error_Loader(sprintf('Malformed bundle template name "%s" (expecting "@bundle/template_name").', $name));
            }
            $bundle = ucfirst(substr($name, 1, $pos - 1));
            $templateName = substr($name, $pos + 1);
        } else {
            $this->cache[$name] = false;
            throw new Twig_Error_Loader(sprintf('Missing bundle in template name "%s" (expecting "@bundle/template_name").', $name));
        }

        $paths = $this->getPathsForBundle($bundle);
        foreach ($paths as $path) {
            if (is_file($path . '/' . $templateName)) {
                return $this->cache[$name] = $path . '/' . $templateName;
            }
        }

        $this->cache[$name] = false;

        throw new Twig_Error_Loader(sprintf('Unable to find template "%s" (looked into: %s).', $templateName, implode(', ', $paths)));
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
            $path = sprintf($path, $bundle);
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

}

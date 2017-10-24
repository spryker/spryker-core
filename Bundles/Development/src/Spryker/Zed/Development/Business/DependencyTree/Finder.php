<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree;

use Symfony\Component\Finder\Finder as SymfonyFinder;

class Finder
{
    /**
     * @var string
     */
    private $bundleDirectory;

    /**
     * @var string
     */
    private $application;

    /**
     * @var string
     */
    private $bundle;

    /**
     * @var string
     */
    private $layer;

    /**
     * @var string
     */
    private $name;

    /**
     * @param string $bundleDirectory
     * @param string $application
     * @param string $bundle
     * @param string $layer
     * @param string $name
     */
    public function __construct($bundleDirectory, $application = '*', $bundle = '*', $layer = '*', $name = '*.php')
    {
        $this->bundleDirectory = $bundleDirectory;
        $this->application = $application;
        $this->bundle = $bundle;
        $this->layer = $layer;
        $this->name = $name;
    }

    /**
     * @return \Symfony\Component\Finder\Finder|\Symfony\Component\Finder\SplFileInfo[]
     */
    public function getFiles()
    {
        $directories = $this->getDirectories();
        foreach ($directories as $key => $directory) {
            if (!glob($directory)) {
                unset($directories[$key]);
            }
        }

        if (count($directories) === 0) {
            return [];
        }

        $finder = new SymfonyFinder();
        $finder->files()->in($directories);

        if ($this->name !== null) {
            $finder->name($this->name);
        }

        return $finder;
    }

    /**
     * @return array
     */
    private function getDirectories()
    {
        if ($this->application === '*') {
            $directories = array_unique(array_merge(
                $this->getZedDirectories(),
                $this->getYvesDirectories(),
                $this->getClientDirectories(),
                $this->getSharedDirectories(),
                $this->getServiceDirectories()
            ));

            return $directories;
        }

        $directoryGetter = 'get' . $this->application . 'Directories';

        return $this->$directoryGetter();
    }

    /**
     * @return array
     */
    private function getZedDirectories()
    {
        if ($this->layer === '*') {
            $this->layer = null;
        }

        return [
            $this->bundleDirectory . '/' . $this->bundle . '/src/Spryker/Zed/' . $this->bundle . '/' . $this->layer,
            $this->bundleDirectory . '/' . $this->bundle . '/tests/*/Spryker/Zed/' . $this->bundle . '/' . $this->layer,
            $this->bundleDirectory . '/' . $this->bundle . '/tests/_support/',
            $this->bundleDirectory . '/' . $this->bundle . '/tests/SprykerTest/',
        ];
    }

    /**
     * @return array
     */
    private function getServiceDirectories()
    {
        return [
            $this->bundleDirectory . '/' . $this->bundle . '/src/Spryker/Service/' . $this->bundle,
            $this->bundleDirectory . '/' . $this->bundle . '/tests/*/Spryker/Service/' . $this->bundle,
        ];
    }

    /**
     * @return array
     */
    private function getYvesDirectories()
    {
        return [
            $this->bundleDirectory . '/' . $this->bundle . '/src/Spryker/Yves/' . $this->bundle,
            $this->bundleDirectory . '/' . $this->bundle . '/tests/*/Spryker/Yves/' . $this->bundle,
            $this->bundleDirectory . '/' . $this->bundle . '/tests/_support/',
            $this->bundleDirectory . '/' . $this->bundle . '/tests/SprykerTest/',
        ];
    }

    /**
     * @return array
     */
    private function getClientDirectories()
    {
        return [
            $this->bundleDirectory . '/' . $this->bundle . '/src/Spryker/Client/' . $this->bundle,
            $this->bundleDirectory . '/' . $this->bundle . '/tests/*/Spryker/Client/' . $this->bundle,
            $this->bundleDirectory . '/' . $this->bundle . '/tests/_support/',
            $this->bundleDirectory . '/' . $this->bundle . '/tests/SprykerTest/',
        ];
    }

    /**
     * @return array
     */
    private function getSharedDirectories()
    {
        return [
            $this->bundleDirectory . '/' . $this->bundle . '/src/Spryker/Shared/' . $this->bundle . '/',
            $this->bundleDirectory . '/' . $this->bundle . '/tests/*/Spryker/Shared/' . $this->bundle . '/',
            $this->bundleDirectory . '/' . $this->bundle . '/tests/_support/',
            $this->bundleDirectory . '/' . $this->bundle . '/tests/SprykerTest/',
        ];
    }
}

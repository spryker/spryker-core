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
    protected $moduleDirectory;

    /**
     * @var string
     */
    protected $application;

    /**
     * @var string
     */
    protected $module;

    /**
     * @var string
     */
    protected $layer;

    /**
     * @var string
     */
    protected $name;

    /**
     * @param string $moduleDirectory
     * @param string $application
     * @param string $module
     * @param string $layer
     * @param string $name
     */
    public function __construct($moduleDirectory, $application = '*', $module = '*', $layer = '*', $name = '*.php')
    {
        $this->moduleDirectory = $moduleDirectory;
        $this->application = $application;
        $this->module = $module;
        $this->layer = $layer;
        $this->name = $name;
    }

    /**
     * @return \Symfony\Component\Finder\Finder|null
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
            return null;
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
        $layer = $this->layer;
        if ($this->layer === '*') {
            $layer = '';
        }

        return [
            $this->moduleDirectory . '/' . $this->module . '/src/Spryker/Zed/' . $this->module . '/' . $layer,
            $this->moduleDirectory . '/' . $this->module . '/tests/_support/',
            $this->moduleDirectory . '/' . $this->module . '/tests/SprykerTest/',
        ];
    }

    /**
     * @return array
     */
    private function getServiceDirectories()
    {
        return [
            $this->moduleDirectory . '/' . $this->module . '/src/Spryker/Service/' . $this->module,
            $this->moduleDirectory . '/' . $this->module . '/tests/_support/',
            $this->moduleDirectory . '/' . $this->module . '/tests/SprykerTest/',
        ];
    }

    /**
     * @return array
     */
    private function getYvesDirectories()
    {
        return [
            $this->moduleDirectory . '/' . $this->module . '/src/Spryker/Yves/' . $this->module,
            $this->moduleDirectory . '/' . $this->module . '/tests/_support/',
            $this->moduleDirectory . '/' . $this->module . '/tests/SprykerTest/',
        ];
    }

    /**
     * @return array
     */
    private function getClientDirectories()
    {
        return [
            $this->moduleDirectory . '/' . $this->module . '/src/Spryker/Client/' . $this->module,
            $this->moduleDirectory . '/' . $this->module . '/tests/_support/',
            $this->moduleDirectory . '/' . $this->module . '/tests/SprykerTest/',
        ];
    }

    /**
     * @return array
     */
    private function getSharedDirectories()
    {
        return [
            $this->moduleDirectory . '/' . $this->module . '/src/Spryker/Shared/' . $this->module . '/',
            $this->moduleDirectory . '/' . $this->module . '/tests/_support/',
            $this->moduleDirectory . '/' . $this->module . '/tests/SprykerTest/',
        ];
    }
}

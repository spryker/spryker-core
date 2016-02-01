<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree;

use Symfony\Component\Finder\Finder as SfFinder;

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
     * @param $bundleDirectory
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
     * @throws \Exception
     *
     * @return \Symfony\Component\Finder\Finder
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
            throw new \Exception('Directories can not be resolved with glob. Maybe you have a wrong path pattern applied.');
        }

        $finder = new SfFinder();
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
            $directories = array_merge(
                $this->getZedDirectories(),
                $this->getYvesDirectories(),
                $this->getClientDirectories(),
                $this->getSharedDirectories()
            );

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
        ];
    }

}

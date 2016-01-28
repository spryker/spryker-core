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
     * @param string $application
     * @param string $bundle
     * @param string $layer
     * @param string $name
     */
    public function __construct($application = '*', $bundle = '*', $layer = '*', $name = '*.php')
    {
        $this->application = $application;
        $this->bundle = $bundle;
        $this->layer = $layer;
        $this->name = $name;
    }

    /**
     * @throws \Exception
     *
     * @return SfFinder
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
            APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/' . $this->bundle . '/src/Spryker/Zed/' . $this->bundle . '/' . $this->layer,
            APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/' . $this->bundle . '/tests/*/Spryker/Zed/' . $this->bundle . '/' . $this->layer,
        ];
    }

    /**
     * @return array
     */
    private function getYvesDirectories()
    {
        return [
            APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/' . $this->bundle . '/src/Spryker/Yves/' . $this->bundle,
            APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/' . $this->bundle . '/tests/*/Spryker/Yves/' . $this->bundle,
        ];
    }

    /**
     * @return array
     */
    private function getClientDirectories()
    {
        return [
            APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/' . $this->bundle . '/src/Spryker/Client/' . $this->bundle,
            APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/' . $this->bundle . '/tests/*/Spryker/Client/' . $this->bundle,
        ];
    }

    /**
     * @return array
     */
    private function getSharedDirectories()
    {
        return [
            APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/' . $this->bundle . '/src/Spryker/Shared/' . $this->bundle . '/',
            APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/' . $this->bundle . '/tests/*/Spryker/Shared/' . $this->bundle . '/',
        ];
    }

}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\ArchitectureSniffer;

use InvalidArgumentException;
use Symfony\Component\Finder\Finder;
use Zend\Filter\FilterInterface;

/**
 * @deprecated Use `AllModuleFinder` instead.
 */
class AllBundleFinder implements AllBundleFinderInterface
{
    /**
     * @var \Symfony\Component\Finder\Finder
     */
    protected $finder;

    /**
     * @var \Zend\Filter\FilterInterface
     */
    protected $filter;

    /**
     * @var array
     */
    protected $projectNamespaces;

    /**
     * @var array
     */
    protected $coreNamespaces;

    /**
     * @param \Symfony\Component\Finder\Finder $finder
     * @param \Zend\Filter\FilterInterface $filter
     * @param array $projectNamespaces
     * @param array $coreNamespaces
     */
    public function __construct(Finder $finder, FilterInterface $filter, $projectNamespaces = [], $coreNamespaces = [])
    {
        $this->finder = $finder;
        $this->filter = $filter;
        $this->projectNamespaces = $projectNamespaces;
        $this->coreNamespaces = $coreNamespaces;
    }

    /**
     * @return array
     */
    public function find()
    {
        $allBundles = [];
        $allBundles = $this->loadProjectBundles($allBundles);
        $allBundles = $this->loadCoreDevelopmentBundles($allBundles);
        $allBundles = $this->loadOtherCoreBundles($allBundles);

        $allBundles = $this->addApplication($allBundles);

        return $allBundles;
    }

    /**
     * @param string $path
     * @param string $namespace
     * @param array $allBundles
     *
     * @return array
     */
    protected function findBundles($path, $namespace, array $allBundles)
    {
        $directories = [];

        try {
            $directories = (new Finder())
                ->directories()
                ->in($path)
                ->depth('== 0');
        } catch (InvalidArgumentException $e) {
            // ~ Directory does not exist. It's not an error.
        }

        foreach ($directories as $dir) {
            $allBundles[] = [
                'bundle' => $dir->getFileName(),
                'namespace' => $namespace,
                'directory' => $dir->getPathName(),
            ];
        }

        return $allBundles;
    }

    /**
     * @param array $allBundles
     *
     * @return array
     */
    protected function loadProjectBundles(array $allBundles)
    {
        foreach ($this->projectNamespaces as $projectNamespace) {
            $path = APPLICATION_SOURCE_DIR . '/' . $projectNamespace . '/*';
            $allBundles = $this->findBundles($path, $projectNamespace, $allBundles);
        }

        return $allBundles;
    }

    /**
     * @param array $allBundles
     *
     * @return array
     */
    protected function loadCoreDevelopmentBundles(array $allBundles)
    {
        $path = APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/*/src/Spryker/*';
        $namespace = 'Spryker';
        $allBundles = $this->findBundles($path, $namespace, $allBundles);

        return $allBundles;
    }

    /**
     * @param array $allBundles
     *
     * @return array
     */
    protected function loadOtherCoreBundles(array $allBundles)
    {
        foreach ($this->coreNamespaces as $coreNamespace) {
            $namespaceDir = $this->filter->filter($coreNamespace);
            $namespaceDir = strtolower($namespaceDir);

            $path = APPLICATION_VENDOR_DIR . '/' . $namespaceDir . '/*/src/*/*';
            $allBundles = $this->findBundles($path, $coreNamespace, $allBundles);
        }

        return $allBundles;
    }

    /**
     * @param array $allBundles
     *
     * @return mixed
     */
    protected function addApplication(array $allBundles)
    {
        foreach ($allBundles as $i => $bundleData) {
            $expl = explode('/', $bundleData['directory']);
            $allBundles[$i]['application'] = $expl[count($expl) - 2];
        }

        return $allBundles;
    }
}

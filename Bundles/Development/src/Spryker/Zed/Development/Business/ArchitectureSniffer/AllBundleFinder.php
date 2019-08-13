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
        $allBundles[] = $this->loadProjectBundles();
        $allBundles[] = $this->loadCoreDevelopmentBundles();
        $allBundles[] = $this->loadOtherCoreBundles();

        $allBundles = $this->addApplication(array_merge(...$allBundles));

        return $allBundles;
    }

    /**
     * @param string $path
     * @param string $namespace
     *
     * @return array
     */
    protected function findBundles($path, $namespace): array
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

        if (!$directories) {
            return [];
        }

        $bundles = [];
        foreach ($directories as $dir) {
            $bundles[] = [
                'bundle' => $dir->getFileName(),
                'namespace' => $namespace,
                'directory' => $dir->getPathName(),
            ];
        }

        return $bundles;
    }

    /**
     * @return array
     */
    protected function loadProjectBundles(): array
    {
        $bundles = [];
        foreach ($this->projectNamespaces as $projectNamespace) {
            $path = APPLICATION_SOURCE_DIR . '/' . $projectNamespace . '/*';
            $bundles = $this->findBundles($path, $projectNamespace);
        }

        return $bundles;
    }

    /**
     * @return array
     */
    protected function loadCoreDevelopmentBundles(): array
    {
        $bundles = [];
        foreach (range('A', 'Z') as $letter) {
            $path = sprintf('%s/spryker/spryker/Bundles/%s*/src/Spryker/*', APPLICATION_VENDOR_DIR, $letter);
            $namespace = 'Spryker';
            $bundles[] = $this->findBundles($path, $namespace);
        }

        return array_merge(...$bundles);
    }

    /**
     * @return array
     */
    protected function loadOtherCoreBundles(): array
    {
        $bundles = [];
        foreach ($this->coreNamespaces as $coreNamespace) {
            $namespaceDir = $this->filter->filter($coreNamespace);
            $namespaceDir = strtolower($namespaceDir);

            $path = APPLICATION_VENDOR_DIR . '/' . $namespaceDir . '/*/src/*/*';
            $bundles = $this->findBundles($path, $coreNamespace);
        }

        return $bundles;
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

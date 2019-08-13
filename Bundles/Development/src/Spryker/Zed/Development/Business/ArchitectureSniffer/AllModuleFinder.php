<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\ArchitectureSniffer;

use InvalidArgumentException;
use Spryker\Zed\Development\DevelopmentConfig;
use Symfony\Component\Finder\Finder;
use Zend\Filter\FilterInterface;

class AllModuleFinder implements AllModuleFinderInterface
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
     * @var \Spryker\Zed\Development\DevelopmentConfig
     */
    protected $developmentConfig;

    /**
     * @param \Symfony\Component\Finder\Finder $finder
     * @param \Zend\Filter\FilterInterface $filter
     * @param \Spryker\Zed\Development\DevelopmentConfig $developmentConfig
     */
    public function __construct(Finder $finder, FilterInterface $filter, DevelopmentConfig $developmentConfig)
    {
        $this->finder = $finder;
        $this->filter = $filter;
        $this->developmentConfig = $developmentConfig;
    }

    /**
     * @return array
     */
    public function find(): array
    {
        $modules = [];
        $modules[] = $this->loadProjectModules();
        $modules[] = $this->loadCoreDevelopmentModules();
        $modules[] = $this->loadOtherCoreModules();

        return $this->addApplication(array_merge(...$modules));
    }

    /**
     * @return array
     */
    protected function loadProjectModules(): array
    {
        $modules = [];
        foreach ($this->developmentConfig->getProjectNamespaces() as $projectNamespace) {
            $path = APPLICATION_SOURCE_DIR . '/' . $projectNamespace . '/*';
            $modules = $this->findModules($path, $projectNamespace);
        }

        return $modules;
    }

    /**
     * @return array
     */
    protected function loadCoreDevelopmentModules(): array
    {
        $modules = [];
        foreach (range('A', 'Z') as $letter) {
            $path = sprintf('%s/spryker/spryker/Bundles/%s*/src/Spryker/*', APPLICATION_VENDOR_DIR, $letter);
            $namespace = 'Spryker';
            $modules[] = $this->findModules($path, $namespace);
        }

        return array_merge(...$modules);
    }

    /**
     * @return array
     */
    protected function loadOtherCoreModules(): array
    {
        $modules = [];
        foreach ($this->developmentConfig->getCoreNamespaces() as $coreNamespace) {
            $namespaceDir = $this->filter->filter($coreNamespace);
            $namespaceDir = strtolower($namespaceDir);

            $path = APPLICATION_VENDOR_DIR . '/' . $namespaceDir . '/*/src/*/*';
            $modules = $this->findModules($path, $coreNamespace);
        }

        return $modules;
    }

    /**
     * @param string $path
     * @param string $namespace
     *
     * @return array
     */
    protected function findModules($path, $namespace): array
    {
        $directories = [];
        $finder = clone $this->finder;

        try {
            $directories = $finder
                ->directories()
                ->in($path)
                ->depth('== 0');
        } catch (InvalidArgumentException $e) {
            // ~ Directory does not exist. It's not an error.
        }

        if (!$directories) {
            return [];
        }

        $modules = [];
        foreach ($directories as $dir) {
            $modules[] = [
                'bundle' => $dir->getFileName(),
                'namespace' => $namespace,
                'directory' => $dir->getPathName(),
            ];
        }

        return $modules;
    }

    /**
     * @param array $modules
     *
     * @return mixed
     */
    protected function addApplication(array $modules)
    {
        foreach ($modules as $i => $moduleData) {
            $moduleDataExploded = explode('/', $moduleData['directory']);
            $modules[$i]['application'] = $moduleDataExploded[count($moduleDataExploded) - 2];
        }

        return $modules;
    }
}

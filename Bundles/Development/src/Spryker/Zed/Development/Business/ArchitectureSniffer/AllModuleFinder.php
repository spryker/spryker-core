<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\ArchitectureSniffer;

use InvalidArgumentException;
use Spryker\Zed\Development\DevelopmentConfig;
use Symfony\Component\Finder\Finder;

class AllModuleFinder implements AllModuleFinderInterface
{
    /**
     * @var \Symfony\Component\Finder\Finder
     */
    protected $finder;

    /**
     * @var \Spryker\Zed\Development\DevelopmentConfig
     */
    protected $developmentConfig;

    /**
     * @param \Symfony\Component\Finder\Finder $finder
     * @param \Spryker\Zed\Development\DevelopmentConfig $developmentConfig
     */
    public function __construct(Finder $finder, DevelopmentConfig $developmentConfig)
    {
        $this->finder = $finder;
        $this->developmentConfig = $developmentConfig;
    }

    /**
     * @return array
     */
    public function find(): array
    {
        $modules = [];
        $modules[] = $this->loadProjectModules();
        $modules[] = $this->loadCoreModules();

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
    protected function loadCoreModules(): array
    {
        $modules = [];
        foreach ($this->developmentConfig->getCoreNamespaces() as $coreNamespace) {
            $pathToNamespace = $this->developmentConfig->getPathToInternalNamespace($coreNamespace);
            foreach (range('a', 'z') as $letter) {
                if (mb_strpos($pathToNamespace, 'Bundles') !== false) {
                    $letter = strtoupper((string)$letter);
                }
                $path = $pathToNamespace . sprintf('%s*/src/*/*', $letter);
                $modules[] = $this->findModules($path, $coreNamespace);
            }
        }

        return array_merge(...$modules);
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
     * @return array
     */
    protected function addApplication(array $modules): array
    {
        foreach ($modules as $i => $moduleData) {
            $moduleDataExploded = explode('/', $moduleData['directory']);
            $modules[$i]['application'] = $moduleDataExploded[count($moduleDataExploded) - 2];
        }

        return $modules;
    }
}

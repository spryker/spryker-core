<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree\Finder\PathBuilder;

use Spryker\Zed\Development\DevelopmentConfig;
use Zend\Filter\FilterChain;
use Zend\Filter\StringToLower;
use Zend\Filter\Word\CamelCaseToDash;

class AbstractPathBuilder
{
    protected const ORGANIZATION = '';

    protected const LOOKUP_NAMESPACES = [];

    /**
     * @var \Spryker\Zed\Development\DevelopmentConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Development\DevelopmentConfig $config
     */
    public function __construct(DevelopmentConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $module
     *
     * @return array
     */
    public function buildPaths(string $module): array
    {
        $filteredModule = $this->filterModule($module);

        $paths = [];
        $basePath = rtrim($this->config->getPathToInternalNamespace(static::ORGANIZATION), '/');
        foreach ($this->config->getApplications() as $application) {
            foreach (static::LOOKUP_NAMESPACES as $srcFolder => $namespace) {
                $paths[] = $this->getPath([$module, $basePath, $srcFolder, $filteredModule, $namespace, $application]);
                $paths[] = $this->getPath([$module, $basePath, $srcFolder, $module, $namespace, $application]);
            }
        }

        return $paths;
    }

    /**
     * @param string[] $pathComponents
     *
     * @return string
     */
    protected function getPath(array $pathComponents): string
    {
        [$module, $basePath, $srcFolder, $filteredModule, $namespace, $application] = $pathComponents;

        return sprintf('%s/%s/%s/%s/%s/%s', $basePath, $filteredModule, $srcFolder, $namespace, $application, $module);
    }

    /**
     * @param string $module
     *
     * @return string
     */
    protected function filterModule(string $module): string
    {
        if ($module === '*') {
            return $module;
        }

        $filterChain = new FilterChain();
        $filterChain
            ->attach(new CamelCaseToDash())
            ->attach(new StringToLower());

        return $filterChain->filter($module);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree\Finder\PathBuilder;

use Zend\Filter\FilterChain;
use Zend\Filter\StringToLower;
use Zend\Filter\Word\CamelCaseToDash;

class SprykerShopPathBuilder implements PathBuilderInterface
{
    /**
     * @var string
     */
    protected $basePath;

    /**
     * @var array
     */
    protected $applications;

    /**
     * @param string $basePath
     * @param array $applications
     */
    public function __construct($basePath, array $applications)
    {
        $this->basePath = $basePath;
        $this->applications = $applications;
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
        foreach ($this->applications as $application) {
            $paths[] = sprintf('%s/%s/src/SprykerShop/%s/%s', $this->basePath, $filteredModule, $application, $module);
            $paths[] = sprintf('%s/%s/src/SprykerShopTest/%s/%s', $this->basePath, $filteredModule, $application, $module);
        }

        return $paths;
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

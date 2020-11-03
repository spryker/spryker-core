<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\DependencyFinder;

use Laminas\Filter\FilterChain;
use Laminas\Filter\StringToLower;
use Laminas\Filter\Word\CamelCaseToDash;

abstract class AbstractFileDependencyFinder implements DependencyFinderInterface
{
    /**
     * @var \Laminas\Filter\FilterChain|null
     */
    protected $filter;

    /**
     * @param string $moduleOrComposerName
     *
     * @return bool
     */
    protected function isExtensionModule(string $moduleOrComposerName): bool
    {
        return (bool)preg_match('/Extension$|-extension$/', $moduleOrComposerName);
    }

    /**
     * @param string $filePath
     *
     * @return bool
     */
    protected function isPluginFile(string $filePath): bool
    {
        return (strpos($filePath, '/Plugin/') !== false);
    }

    /**
     * @param string $filePath
     *
     * @return bool
     */
    protected function isTestFile(string $filePath)
    {
        return !strpos($filePath, '/src/');
    }

    /**
     * @param string $organizationName
     * @param string $moduleName
     *
     * @return string
     */
    protected function buildComposerName(string $organizationName, string $moduleName): string
    {
        $filter = $this->getFilter();
        $composerName = sprintf('%s/%s', $filter->filter($organizationName), $filter->filter($moduleName));

        return $composerName;
    }

    /**
     * @return \Laminas\Filter\FilterChain
     */
    protected function getFilter(): FilterChain
    {
        if (!$this->filter) {
            $filter = new FilterChain();
            $filter->attach(new CamelCaseToDash())
                ->attach(new StringToLower());

            $this->filter = $filter;
        }

        return $this->filter;
    }
}

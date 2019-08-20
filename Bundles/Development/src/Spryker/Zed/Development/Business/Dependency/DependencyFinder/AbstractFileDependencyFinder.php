<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\DependencyFinder;

use Zend\Filter\FilterChain;
use Zend\Filter\StringToLower;
use Zend\Filter\Word\CamelCaseToDash;

abstract class AbstractFileDependencyFinder implements DependencyFinderInterface
{
    /**
     * @var \Zend\Filter\FilterChain|null
     */
    protected $filter;

    /**
     * @param string $moduleOrComposerName
     *
     * @return bool
     */
    protected function isExtensionModule(string $moduleOrComposerName): bool
    {
        return preg_match('/Extension$|-extension$/', $moduleOrComposerName);
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
     * @return \Zend\Filter\FilterChain
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

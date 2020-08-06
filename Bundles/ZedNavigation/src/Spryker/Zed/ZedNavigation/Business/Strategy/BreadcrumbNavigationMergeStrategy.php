<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Business\Strategy;

use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use Spryker\Zed\ZedNavigation\ZedNavigationConfig;
use Zend\Config\Config;

class BreadcrumbNavigationMergeStrategy implements NavigationMergeStrategyInterface
{
    /**
     * @see \Spryker\Zed\ZedNavigation\Business\Model\Formatter\MenuFormatter::PAGES
     */
    protected const PAGES = 'pages';

    /**
     * @see \Spryker\Zed\ZedNavigation\Business\Model\Formatter\MenuFormatter::BUNDLE
     */
    protected const BUNDLE = 'bundle';

    /**
     * @return string
     */
    public function getMergeStrategy(): string
    {
        return ZedNavigationConfig::BREADCRUMB_MERGE_STRATEGY;
    }

    /**
     * @param \Zend\Config\Config $navigationDefinition
     * @param \Zend\Config\Config $rootDefinition
     * @param \Zend\Config\Config $coreNavigationDefinition
     *
     * @return array
     */
    public function mergeNavigation(Config $navigationDefinition, Config $rootDefinition, Config $coreNavigationDefinition): array
    {
        $rootDefinitionData = $rootDefinition->toArray();
        $coreNavigationDefinitionData = $coreNavigationDefinition->toArray();
        foreach ($rootDefinitionData as &$rootNavigationElement) {
            if (!$this->hasPages($rootNavigationElement)) {
                continue;
            }

            $rootNavigationElement = $this->mergeNavigationPages(
                $rootNavigationElement,
                $coreNavigationDefinitionData
            );
        }

        return $rootDefinitionData;
    }

    /**
     * @param array $rootNavigationElement
     * @param array $coreNavigationDefinitionData
     *
     * @return array
     */
    protected function mergeNavigationPages(array $rootNavigationElement, array $coreNavigationDefinitionData): array
    {
        foreach ($rootNavigationElement[static::PAGES] as $navigationName => &$childNavigationElement) {
            $foundNavigationElement = $this->getNavigationInNavigationData(
                $coreNavigationDefinitionData,
                $childNavigationElement,
                $navigationName
            );

            $childNavigationElement = $this->mergeNavigationElementPages($foundNavigationElement, $childNavigationElement);
        }

        return $rootNavigationElement;
    }

    /**
     * @param array $navigationElement
     * @param array $rootNavigationElement
     *
     * @return array
     */
    protected function mergeNavigationElementPages(array $navigationElement, array $rootNavigationElement): array
    {
        if (!$this->hasPages($navigationElement)) {
            return $rootNavigationElement;
        }

        if (!$this->hasPages($rootNavigationElement)) {
            $rootNavigationElement[static::PAGES] = $navigationElement[static::PAGES];

            return $rootNavigationElement;
        }

        $rootNavigationElement[static::PAGES] = array_merge_recursive(
            $navigationElement[static::PAGES],
            $rootNavigationElement[static::PAGES]
        );

        return $rootNavigationElement;
    }

    /**
     * @param array $navigationDefinitionData
     * @param array $rootNavigationElement
     * @param string $navigationName
     *
     * @return array
     */
    protected function getNavigationInNavigationData(array $navigationDefinitionData, array $rootNavigationElement, string $navigationName): array
    {
        $iterator = new RecursiveArrayIterator($navigationDefinitionData);
        $navigationRecursiveIterator = new RecursiveIteratorIterator(
            $iterator,
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($navigationRecursiveIterator as $key => $navigationElement) {
            if ($key !== $navigationName) {
                continue;
            }

            if ($this->isSameModule($navigationElement, $rootNavigationElement)) {
                return $navigationElement;
            }
        }

        return [];
    }

    /**
     * @param array $navigationElement
     *
     * @return bool
     */
    protected function hasPages(array $navigationElement): bool
    {
        return isset($navigationElement[static::PAGES]);
    }

    /**
     * @param array $navigationElement
     * @param array $rootNavigationElement
     *
     * @return bool
     */
    protected function isSameModule(array $navigationElement, array $rootNavigationElement): bool
    {
        return isset($navigationElement[static::BUNDLE])
            && $navigationElement[static::BUNDLE] === $rootNavigationElement[static::BUNDLE];
    }
}

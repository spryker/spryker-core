<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Business\Strategy;

use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use Spryker\Zed\ZedNavigation\Business\Model\Formatter\MenuFormatter;
use Spryker\Zed\ZedNavigation\ZedNavigationConfig;
use Zend\Config\Config;

class NavigationBreadcrumbsMergeStrategy implements NavigationMergeStrategyInterface
{
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
        foreach ($rootDefinitionData as &$rootNavigation) {
            if (!$this->hasPages($rootNavigation)) {
                continue;
            }

            foreach ($rootNavigation[MenuFormatter::PAGES] as $navigationName => &$rootNavigationElement) {
                $foundNavigationElement = $this->getNavigationInNavigationData(
                    $coreNavigationDefinitionData,
                    $rootNavigationElement,
                    $navigationName
                );

                $rootNavigationElement = $this->mergeNavigationElementPages($foundNavigationElement, $rootNavigationElement);
            }
        }

        return $rootDefinitionData;
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
            $rootNavigationElement[MenuFormatter::PAGES] = $navigationElement[MenuFormatter::PAGES];

            return $rootNavigationElement;
        }

        $rootNavigationElement[MenuFormatter::PAGES] = array_merge_recursive(
            $navigationElement[MenuFormatter::PAGES],
            $rootNavigationElement[MenuFormatter::PAGES]
        );

        return $rootNavigationElement;
    }

    /**
     * @param array $navigationData
     * @param array $rootNavigationElement
     * @param string $navigationName
     *
     * @return array
     */
    protected function getNavigationInNavigationData(array $navigationData, array $rootNavigationElement, string $navigationName): array
    {
        $iterator = new RecursiveArrayIterator($navigationData);
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
        return isset($navigationElement[MenuFormatter::PAGES]);
    }

    /**
     * @param array $navigationElement
     * @param array $rootNavigationElement
     *
     * @return bool
     */
    protected function isSameModule(array $navigationElement, array $rootNavigationElement): bool
    {
        return isset($navigationElement[MenuFormatter::BUNDLE])
            && $navigationElement[MenuFormatter::BUNDLE] === $rootNavigationElement[MenuFormatter::BUNDLE];
    }
}

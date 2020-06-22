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
     * @param array $coreNavigationDefinitionData
     *
     * @return array
     */
    public function mergeNavigation(Config $navigationDefinition, Config $rootDefinition, array $coreNavigationDefinitionData): array
    {
        $rootDefinitionData = $rootDefinition->toArray();
        foreach ($rootDefinitionData as &$rootNavigation) {
            if (!$this->hasPages($rootNavigation)) {
                continue;
            }

            foreach ($rootNavigation[MenuFormatter::PAGES] as $navigationName => &$rootNavigationElement) {
                $navigationInMergedNavigationData = $this->getNavigationInMergedNavigationData(
                    $coreNavigationDefinitionData,
                    $rootNavigationElement,
                    $navigationName
                );

                $rootNavigationElement = $this->mergeNavigationElementPages($navigationInMergedNavigationData, $rootNavigationElement);
            }
        }

        return $rootDefinitionData;
    }

    /**
     * @param array $navigationInMergedNavigationData
     * @param array $rootNavigationElement
     *
     * @return array
     */
    protected function mergeNavigationElementPages(array $navigationInMergedNavigationData, array $rootNavigationElement): array
    {
        if (!$this->hasPages($navigationInMergedNavigationData)) {
            return $rootNavigationElement;
        }

        if (!$this->hasPages($rootNavigationElement)) {
            $rootNavigationElement[MenuFormatter::PAGES] = $navigationInMergedNavigationData[MenuFormatter::PAGES];

            return $rootNavigationElement;
        }

        $rootNavigationElement[MenuFormatter::PAGES] = $this->mergeNavigationElementsRecursively(
            $navigationInMergedNavigationData[MenuFormatter::PAGES],
            $rootNavigationElement[MenuFormatter::PAGES]
        );

        return $rootNavigationElement;
    }

    /**
     * @param array $navigationInMergedNavigationData
     * @param array $rootNavigationElement
     *
     * @return array
     */
    protected function mergeNavigationElementsRecursively(
        array $navigationInMergedNavigationData,
        array $rootNavigationElement
    ): array {
        $mergedNavigationData = $navigationInMergedNavigationData;
        foreach ($rootNavigationElement as $navigationName => &$navigation) {
            if (is_array($navigation) && isset($mergedNavigationData[$navigationName]) && is_array($mergedNavigationData[$navigationName])) {
                $mergedNavigationData[$navigationName] = $this->mergeNavigationElementsRecursively($mergedNavigationData[$navigationName], $navigation);
            }
        }

        return $mergedNavigationData;
    }

    /**
     * @param array $mergedNavigationData
     * @param array $rootNavigationElement
     * @param string $navigationName
     *
     * @return array
     */
    protected function getNavigationInMergedNavigationData(array $mergedNavigationData, array $rootNavigationElement, string $navigationName): array
    {
        $iterator = new RecursiveArrayIterator($mergedNavigationData);
        $navigationRecursiveIterator = new RecursiveIteratorIterator(
            $iterator,
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($navigationRecursiveIterator as $key => $navigation) {
            if (
                $key === $navigationName
                && $this->hasBundle($navigation)
                && $navigation[MenuFormatter::BUNDLE] === $rootNavigationElement[MenuFormatter::BUNDLE]
            ) {
                return $navigation;
            }
        }

        return [];
    }

    /**
     * @param array $navigationData
     *
     * @return bool
     */
    protected function hasPages(array $navigationData): bool
    {
        return isset($navigationData[MenuFormatter::PAGES]);
    }

    /**
     * @param array $navigationData
     *
     * @return bool
     */
    protected function hasBundle(array $navigationData): bool
    {
        return isset($navigationData[MenuFormatter::BUNDLE]);
    }
}

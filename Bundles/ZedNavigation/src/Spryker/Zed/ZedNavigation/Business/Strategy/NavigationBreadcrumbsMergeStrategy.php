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
     * @param array $navigationDefinitionData
     * @param array $rootDefinitionData
     *
     * @return array
     */
    public function mergeNavigation(array $navigationDefinitionData, array $rootDefinitionData): array
    {
        $mergedNavigationData = $this->getMergedNavigationData($navigationDefinitionData);
        foreach ($rootDefinitionData as &$rootNavigation) {
            if (!$this->hasPages($rootNavigation)) {
                continue;
            }

            foreach ($rootNavigation[MenuFormatter::PAGES] as $navigationName => &$rootNavigationElement) {
                $navigationInMergedNavigationData = $this->getNavigationInMergedNavigationData(
                    $mergedNavigationData,
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
     * @param string $navigationName
     *
     * @return array
     */
    protected function getNavigationInMergedNavigationData(array $mergedNavigationData, string $navigationName): array
    {
        $iterator = new RecursiveArrayIterator($mergedNavigationData);
        $navigationRecursiveIterator = new RecursiveIteratorIterator(
            $iterator,
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($navigationRecursiveIterator as $key => $navigation) {
            if ($key === $navigationName) {
                return $navigation;
            }
        }

        return [];
    }

    /**
     * @param array $navigationDefinitionData
     *
     * @return array
     */
    protected function getMergedNavigationData(array $navigationDefinitionData): array
    {
        $navigationData = [];
        foreach ($navigationDefinitionData as $navigationName => &$navigation) {
            if (!$this->hasPages($navigation)) {
                continue;
            }

            $navigationData = array_merge_recursive($navigationData, $navigation[MenuFormatter::PAGES]);
            $navigationData = array_merge($navigationData, [$navigationName => $navigation]);
        }

        return $navigationData;
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
}

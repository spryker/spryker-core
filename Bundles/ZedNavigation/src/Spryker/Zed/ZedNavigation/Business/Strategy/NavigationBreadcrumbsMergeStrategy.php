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
     * @param array $secondLevelNavigationData
     *
     * @return array
     */
    public function mergeNavigation(array $navigationDefinitionData, array $rootDefinitionData, array $secondLevelNavigationData): array
    {
        foreach ($rootDefinitionData as &$rootNavigation) {
            if (!isset($rootNavigation[MenuFormatter::PAGES])) {
                continue;
            }

            foreach ($rootNavigation[MenuFormatter::PAGES] as $navigationName => &$rootNavigationElement) {
                $navigationInSecondLevelNavigationData = $this->getNavigationInSecondLevelNavigationData(
                    $secondLevelNavigationData,
                    $navigationName
                );

                $rootNavigationElement = $this->mergeNavigationElementPages($navigationInSecondLevelNavigationData, $rootNavigationElement);
            }
        }

        return $rootDefinitionData;
    }

    /**
     * @param array $navigationInSecondLevelNavigationData
     * @param array $rootNavigationElement
     *
     * @return array
     */
    protected function mergeNavigationElementPages(array $navigationInSecondLevelNavigationData, array $rootNavigationElement): array
    {
        if (isset($navigationInSecondLevelNavigationData[MenuFormatter::PAGES])) {
            if (!isset($rootNavigationElement[MenuFormatter::PAGES])) {
                $rootNavigationElement[MenuFormatter::PAGES] = $navigationInSecondLevelNavigationData[MenuFormatter::PAGES];
            }

            $rootNavigationElement[MenuFormatter::PAGES] = $this->mergeNavigationElementsRecursively(
                $navigationInSecondLevelNavigationData[MenuFormatter::PAGES],
                $rootNavigationElement[MenuFormatter::PAGES]
            );
        }

        return $rootNavigationElement;
    }

    /**
     * @param array $navigationInSecondLevelNavigationData
     * @param array $rootNavigationElement
     *
     * @return array
     */
    protected function mergeNavigationElementsRecursively(
        array $navigationInSecondLevelNavigationData,
        array $rootNavigationElement
    ): array {
        $mergedNavigationData = $navigationInSecondLevelNavigationData;
        foreach ($rootNavigationElement as $key => &$value) {
            if (is_array($value) && isset($mergedNavigationData[$key]) && is_array($mergedNavigationData[$key])) {
                $mergedNavigationData[$key] = $this->mergeNavigationElementsRecursively($mergedNavigationData[$key], $value);
            }
        }

        return $mergedNavigationData;
    }

    /**
     * @param array $secondLevelNavigationData
     * @param string $navigationName
     *
     * @return array
     */
    protected function getNavigationInSecondLevelNavigationData(array $secondLevelNavigationData, string $navigationName): array
    {
        $iterator = new RecursiveArrayIterator($secondLevelNavigationData);
        $recursive = new RecursiveIteratorIterator(
            $iterator,
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($recursive as $key => $value) {
            if ($key === $navigationName) {
                return $value;
            }
        }

        return [];
    }
}

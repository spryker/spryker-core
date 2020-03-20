<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Business\Filter;

use Generated\Shared\Transfer\NavigationItemCollectionTransfer;
use Generated\Shared\Transfer\NavigationItemTransfer;
use Spryker\Zed\ZedNavigation\Business\Model\Formatter\MenuFormatter;

class NavigationItemFilter implements NavigationItemFilterInterface
{
    /**
     * @var \Spryker\Zed\ZedNavigationExtension\Dependency\Plugin\NavigationItemCollectionFilterPluginInterface[]
     */
    protected $navigationItemCollectionFilterPlugins;

    /**
     * @param \Spryker\Zed\ZedNavigationExtension\Dependency\Plugin\NavigationItemCollectionFilterPluginInterface[] $navigationItemCollectionFilterPlugins
     */
    public function __construct(array $navigationItemCollectionFilterPlugins)
    {
        $this->navigationItemCollectionFilterPlugins = $navigationItemCollectionFilterPlugins;
    }

    /**
     * @param array $navigationItems
     *
     * @return array
     */
    public function filterNavigationItems(array $navigationItems): array
    {
        $navigationItemCollectionTransfer = new NavigationItemCollectionTransfer();
        $navigationItemCollectionTransfer = $this->mapNavigationItemsToNavigationItemCollectionTransfer(
            $navigationItems,
            $navigationItemCollectionTransfer
        );

        foreach ($this->navigationItemCollectionFilterPlugins as $navigationItemCollectionFilterPlugin) {
            $navigationItemCollectionTransfer = $navigationItemCollectionFilterPlugin->filter(
                $navigationItemCollectionTransfer
            );
        }

        return $this->filterNavigationItemsByNavigationItemCollectionTransfer(
            $navigationItems,
            $navigationItemCollectionTransfer
        );
    }

    /**
     * @param array $navigationItems
     * @param \Generated\Shared\Transfer\NavigationItemCollectionTransfer $navigationItemCollectionTransfer
     *
     * @return array
     */
    protected function filterNavigationItemsByNavigationItemCollectionTransfer(
        array $navigationItems,
        NavigationItemCollectionTransfer $navigationItemCollectionTransfer
    ): array {
        if (!$navigationItemCollectionTransfer->getNavigationItems()->count()) {
            return [];
        }

        $filteredNavigationItems = [];

        foreach ($navigationItems as $navigationItem) {
            if ($this->hasNestedNavigationItems($navigationItem)) {
                $nestedNavigationItems = $this->filterNavigationItemsByNavigationItemCollectionTransfer(
                    $navigationItem[MenuFormatter::PAGES],
                    $navigationItemCollectionTransfer
                );

                if ($nestedNavigationItems) {
                    $navigationItem[MenuFormatter::PAGES] = $nestedNavigationItems;
                    $filteredNavigationItems[] = $navigationItem;
                }

                continue;
            }

            if (
                $navigationItemCollectionTransfer->getNavigationItems()
                    ->offsetExists($this->getNavigationItemKey($navigationItem))
            ) {
                $filteredNavigationItems[] = $navigationItem;
            }
        }

        return $filteredNavigationItems;
    }

    /**
     * @param array $navigationItems
     * @param \Generated\Shared\Transfer\NavigationItemCollectionTransfer $navigationItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationItemCollectionTransfer
     */
    protected function mapNavigationItemsToNavigationItemCollectionTransfer(
        array $navigationItems,
        NavigationItemCollectionTransfer $navigationItemCollectionTransfer
    ): NavigationItemCollectionTransfer {
        foreach ($navigationItems as $navigationItem) {
            if ($this->hasNestedNavigationItems($navigationItem)) {
                $navigationItemCollectionTransfer = $this->mapNavigationItemsToNavigationItemCollectionTransfer(
                    $navigationItem[MenuFormatter::PAGES],
                    $navigationItemCollectionTransfer
                );

                continue;
            }

            $navigationItemTransfer = (new NavigationItemTransfer())
                ->setModule($navigationItem[MenuFormatter::BUNDLE])
                ->setController($navigationItem[MenuFormatter::CONTROLLER])
                ->setAction($navigationItem[MenuFormatter::ACTION]);
            $navigationItemCollectionTransfer->addNavigationItem(
                $this->getNavigationItemKey($navigationItem),
                $navigationItemTransfer
            );
        }

        return $navigationItemCollectionTransfer;
    }

    /**
     * @param array $navigationItem
     *
     * @return bool
     */
    protected function hasNestedNavigationItems(array $navigationItem): bool
    {
        return isset($navigationItem[MenuFormatter::PAGES]);
    }

    /**
     * @param array $navigationItem
     *
     * @return string
     */
    protected function getNavigationItemKey(array $navigationItem): string
    {
        return sprintf(
            '%s:%s:%s',
            $navigationItem[MenuFormatter::BUNDLE],
            $navigationItem[MenuFormatter::CONTROLLER],
            $navigationItem[MenuFormatter::ACTION]
        );
    }
}

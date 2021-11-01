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
     * @var array<\Spryker\Zed\ZedNavigationExtension\Dependency\Plugin\NavigationItemFilterPluginInterface>
     */
    protected $navigationItemFilterPlugins;

    /**
     * @var array<\Spryker\Zed\ZedNavigationExtension\Dependency\Plugin\NavigationItemCollectionFilterPluginInterface>
     */
    protected $navigationItemCollectionFilterPlugins;

    /**
     * @param array<\Spryker\Zed\ZedNavigationExtension\Dependency\Plugin\NavigationItemFilterPluginInterface> $navigationItemFilterPlugins
     * @param array<\Spryker\Zed\ZedNavigationExtension\Dependency\Plugin\NavigationItemCollectionFilterPluginInterface> $navigationItemCollectionFilterPlugins
     */
    public function __construct(array $navigationItemFilterPlugins, array $navigationItemCollectionFilterPlugins)
    {
        $this->navigationItemFilterPlugins = $navigationItemFilterPlugins;
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
            $navigationItemCollectionTransfer,
        );
        $navigationItemCollectionTransfer = $this->applyNavigationItemCollectionFilterPlugins(
            $navigationItemCollectionTransfer,
        );

        return $this->filterNavigationItemsByNavigationItemCollectionTransfer(
            $navigationItems,
            $navigationItemCollectionTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationItemCollectionTransfer $navigationItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationItemCollectionTransfer
     */
    protected function applyNavigationItemCollectionFilterPlugins(
        NavigationItemCollectionTransfer $navigationItemCollectionTransfer
    ): NavigationItemCollectionTransfer {
        foreach ($this->navigationItemCollectionFilterPlugins as $navigationItemCollectionFilterPlugin) {
            $navigationItemCollectionTransfer = $navigationItemCollectionFilterPlugin->filter(
                $navigationItemCollectionTransfer,
            );
        }

        return $navigationItemCollectionTransfer;
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
                    $navigationItemCollectionTransfer,
                );

                if ($nestedNavigationItems) {
                    $navigationItem[MenuFormatter::PAGES] = $nestedNavigationItems;
                    $filteredNavigationItems[] = $navigationItem;
                }

                continue;
            }

            $navigationItemKey = $this->getNavigationItemKey($navigationItem);

            if (!$navigationItemCollectionTransfer->getNavigationItems()->offsetExists($navigationItemKey)) {
                continue;
            }

            $navigationItemTransfer = $navigationItemCollectionTransfer->getNavigationItems()
                ->offsetGet($navigationItemKey);

            if ($this->isNavigationItemVisible($navigationItemTransfer)) {
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
                    $navigationItemCollectionTransfer,
                );

                continue;
            }

            $navigationItemTransfer = (new NavigationItemTransfer())
                ->fromArray($navigationItem, true)
                ->setModule($navigationItem[MenuFormatter::BUNDLE] ?? null);
            $navigationItemCollectionTransfer->addNavigationItem(
                $this->getNavigationItemKey($navigationItem),
                $navigationItemTransfer,
            );
        }

        return $navigationItemCollectionTransfer;
    }

    /**
     * @param array<string, mixed> $navigationItem
     *
     * @return bool
     */
    protected function hasNestedNavigationItems(array $navigationItem): bool
    {
        return isset($navigationItem[MenuFormatter::PAGES]);
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationItemTransfer $navigationItemTransfer
     *
     * @return bool
     */
    protected function isNavigationItemVisible(NavigationItemTransfer $navigationItemTransfer): bool
    {
        foreach ($this->navigationItemFilterPlugins as $navigationItemFilterPlugin) {
            if (!$navigationItemFilterPlugin->isVisible($navigationItemTransfer)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array<string> $navigationItem
     *
     * @return string
     */
    protected function getNavigationItemKey(array $navigationItem): string
    {
        if (
            isset($navigationItem[MenuFormatter::BUNDLE])
            && isset($navigationItem[MenuFormatter::CONTROLLER])
            && isset($navigationItem[MenuFormatter::ACTION])
        ) {
            return sprintf(
                '%s:%s:%s',
                $navigationItem[MenuFormatter::BUNDLE],
                $navigationItem[MenuFormatter::CONTROLLER],
                $navigationItem[MenuFormatter::ACTION],
            );
        }

        return $navigationItem[MenuFormatter::URI];
    }
}

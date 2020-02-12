<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Business\Model;

use Generated\Shared\Transfer\NavigationItemTransfer;
use Generated\Shared\Transfer\RuleTransfer;
use Spryker\Zed\ZedNavigation\Business\Model\Collector\ZedNavigationCollectorInterface;
use Spryker\Zed\ZedNavigation\Business\Model\Extractor\PathExtractorInterface;
use Spryker\Zed\ZedNavigation\Business\Model\Formatter\MenuFormatterInterface;

class ZedNavigationBuilder
{
    public const MENU = 'menu';
    public const PATH = 'path';
    protected const NAVIGATION_ITEM_PAGES_KEY = 'pages';

    /**
     * @var \Spryker\Zed\ZedNavigation\Business\Model\Collector\ZedNavigationCollectorInterface
     */
    private $navigationCollector;

    /**
     * @var \Spryker\Zed\ZedNavigation\Business\Model\Formatter\MenuFormatterInterface
     */
    private $menuFormatter;

    /**
     * @var \Spryker\Zed\ZedNavigation\Business\Model\Extractor\PathExtractorInterface
     */
    private $pathExtractor;

    /**
     * @var \Spryker\Zed\ZedNavigationExtension\Dependency\Plugin\NavigationItemFilterPluginInterface[]
     */
    protected $navigationItemFilterPlugins;

    /**
     * @param \Spryker\Zed\ZedNavigation\Business\Model\Collector\ZedNavigationCollectorInterface $navigationCollector
     * @param \Spryker\Zed\ZedNavigation\Business\Model\Formatter\MenuFormatterInterface $menuFormatter
     * @param \Spryker\Zed\ZedNavigation\Business\Model\Extractor\PathExtractorInterface $pathExtractor
     * @param \Spryker\Zed\ZedNavigationExtension\Dependency\Plugin\NavigationItemFilterPluginInterface[] $navigationItemFilterPlugins
     */
    public function __construct(
        ZedNavigationCollectorInterface $navigationCollector,
        MenuFormatterInterface $menuFormatter,
        PathExtractorInterface $pathExtractor,
        array $navigationItemFilterPlugins
    ) {
        $this->navigationCollector = $navigationCollector;
        $this->menuFormatter = $menuFormatter;
        $this->pathExtractor = $pathExtractor;
        $this->navigationItemFilterPlugins = $navigationItemFilterPlugins;
    }

    /**
     * @param string $pathInfo
     *
     * @return array
     */
    public function build($pathInfo)
    {
        $navigationItems = $this->navigationCollector->getNavigation();
        $navigationItems = $this->filterNavigationItems($navigationItems);

        $menu = $this->menuFormatter->formatMenu($navigationItems, $pathInfo, false);
        $breadcrumb = $this->menuFormatter->formatMenu($navigationItems, $pathInfo, true);
        $path = $this->pathExtractor->extractPathFromMenu($breadcrumb);

        return [
            self::MENU => $menu,
            self::PATH => $path,
        ];
    }

    /**
     * @param array $navigationItems
     *
     * @return array
     */
    protected function filterNavigationItems(array $navigationItems): array
    {
        $filteredNavigationItems = [];
        foreach ($navigationItems as $navigationItem) {
            if ($this->hasNestedNavigationItems($navigationItem)) {
                $filteredNestedNavigationItems = $this->filterNavigationItems($navigationItem[static::NAVIGATION_ITEM_PAGES_KEY]);
                if ($filteredNestedNavigationItems) {
                    $navigationItem[static::NAVIGATION_ITEM_PAGES_KEY] = $filteredNestedNavigationItems;
                    $filteredNavigationItems[] = $navigationItem;
                }

                continue;
            }

            if ($this->isNavigationItemVisible($navigationItem)) {
                $filteredNavigationItems[] = $navigationItem;
            }
        }

        return $filteredNavigationItems;
    }

    /**
     * @param array $navigationItem
     *
     * @return bool
     */
    protected function isNavigationItemVisible(array $navigationItem): bool
    {
        $navigationItemTransfer = (new NavigationItemTransfer())
            ->setModule($navigationItem[RuleTransfer::BUNDLE])
            ->setController($navigationItem[RuleTransfer::CONTROLLER])
            ->setAction($navigationItem[RuleTransfer::ACTION]);

        foreach ($this->navigationItemFilterPlugins as $navigationItemFilterPlugin) {
            if (!$navigationItemFilterPlugin->isVisible($navigationItemTransfer)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $navigationItem
     *
     * @return bool
     */
    protected function hasNestedNavigationItems(array $navigationItem): bool
    {
        return isset($navigationItem[static::NAVIGATION_ITEM_PAGES_KEY]);
    }
}

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
    public const PAGES = 'pages';

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
     * @var \Spryker\Zed\NavigationExtension\Dependency\Plugin\NavigationItemFilterPluginInterface[]
     */
    protected $navigationItemFilterPlugins;

    /**
     * @param \Spryker\Zed\ZedNavigation\Business\Model\Collector\ZedNavigationCollectorInterface $navigationCollector
     * @param \Spryker\Zed\ZedNavigation\Business\Model\Formatter\MenuFormatterInterface $menuFormatter
     * @param \Spryker\Zed\ZedNavigation\Business\Model\Extractor\PathExtractorInterface $pathExtractor
     * @param \Spryker\Zed\NavigationExtension\Dependency\Plugin\NavigationItemFilterPluginInterface[] $navigationItemFilterPlugins
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
        $navigationPages = $this->navigationCollector->getNavigation();
        $navigationPages = $this->filterItems($navigationPages);

        $menu = $this->menuFormatter->formatMenu($navigationPages, $pathInfo, false);
        $breadcrumb = $this->menuFormatter->formatMenu($navigationPages, $pathInfo, true);
        $path = $this->pathExtractor->extractPathFromMenu($breadcrumb);

        return [
            self::MENU => $menu,
            self::PATH => $path,
        ];
    }

    /**
     * @param array $navigationItems

     * @return array
     */
    protected function filterItems(array $navigationItems): array
    {
        foreach ($navigationItems as $itemKey => $item) {
            if (!isset($item[RuleTransfer::BUNDLE], $item[RuleTransfer::CONTROLLER], $item[RuleTransfer::ACTION])) {
                $navigationItems[$itemKey][static::PAGES] = $this->filterItems($item[static::PAGES]);
                if (!$navigationItems[$itemKey][static::PAGES]) {
                    unset($navigationItems[$itemKey]);
                }

                continue;
            }

            $itemTransfer = (new NavigationItemTransfer())
                ->setModule($item[RuleTransfer::BUNDLE])
                ->setController($item[RuleTransfer::CONTROLLER])
                ->setAction($item[RuleTransfer::ACTION]);

            foreach ($this->navigationItemFilterPlugins as $plugin) {
                if (!$plugin->isVisible($itemTransfer)) {
                    unset($navigationItems[$itemKey]);
                    break;
                }
            }
        }

        return $navigationItems;
    }
}

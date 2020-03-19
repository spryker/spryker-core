<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Business\Model;

use Spryker\Zed\ZedNavigation\Business\Model\Collector\ZedNavigationCollectorInterface;
use Spryker\Zed\ZedNavigation\Business\Model\Extractor\PathExtractorInterface;
use Spryker\Zed\ZedNavigation\Business\Model\Filter\NavigationItemFilterInterface;
use Spryker\Zed\ZedNavigation\Business\Model\Formatter\MenuFormatterInterface;

class ZedNavigationBuilder
{
    public const MENU = 'menu';
    public const PATH = 'path';

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
     * @var \Spryker\Zed\ZedNavigation\Business\Model\Filter\NavigationItemFilterInterface
     */
    protected $navigationItemFilter;

    /**
     * @param \Spryker\Zed\ZedNavigation\Business\Model\Collector\ZedNavigationCollectorInterface $navigationCollector
     * @param \Spryker\Zed\ZedNavigation\Business\Model\Formatter\MenuFormatterInterface $menuFormatter
     * @param \Spryker\Zed\ZedNavigation\Business\Model\Extractor\PathExtractorInterface $pathExtractor
     * @param \Spryker\Zed\ZedNavigation\Business\Model\Filter\NavigationItemFilterInterface $navigationItemFilter
     */
    public function __construct(
        ZedNavigationCollectorInterface $navigationCollector,
        MenuFormatterInterface $menuFormatter,
        PathExtractorInterface $pathExtractor,
        NavigationItemFilterInterface $navigationItemFilter
    ) {
        $this->navigationCollector = $navigationCollector;
        $this->menuFormatter = $menuFormatter;
        $this->pathExtractor = $pathExtractor;
        $this->navigationItemFilter = $navigationItemFilter;
    }

    /**
     * @param string $pathInfo
     *
     * @return array
     */
    public function build($pathInfo)
    {
        $navigationItems = $this->navigationCollector->getNavigation();
        $navigationItems = $this->navigationItemFilter->filterNavigationItems($navigationItems);

        $menu = $this->menuFormatter->formatMenu($navigationItems, $pathInfo, false);
        $breadcrumb = $this->menuFormatter->formatMenu($navigationItems, $pathInfo, true);
        $path = $this->pathExtractor->extractPathFromMenu($breadcrumb);

        return [
            self::MENU => $menu,
            self::PATH => $path,
        ];
    }
}

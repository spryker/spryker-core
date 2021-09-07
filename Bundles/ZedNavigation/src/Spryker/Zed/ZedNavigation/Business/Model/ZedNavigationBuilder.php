<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Business\Model;

use Spryker\Zed\ZedNavigation\Business\Filter\NavigationItemFilterInterface;
use Spryker\Zed\ZedNavigation\Business\Model\Collector\ZedNavigationCollectorInterface;
use Spryker\Zed\ZedNavigation\Business\Model\Extractor\PathExtractorInterface;
use Spryker\Zed\ZedNavigation\Business\Model\Formatter\MenuFormatterInterface;
use Spryker\Zed\ZedNavigation\ZedNavigationConfig;

class ZedNavigationBuilder
{
    /**
     * @var string
     */
    public const MENU = 'menu';
    /**
     * @var string
     */
    public const PATH = 'path';

    /**
     * @var \Spryker\Zed\ZedNavigation\Business\Model\Collector\ZedNavigationCollectorInterface
     */
    protected $navigationCollector;

    /**
     * @var \Spryker\Zed\ZedNavigation\Business\Model\Formatter\MenuFormatterInterface
     */
    protected $menuFormatter;

    /**
     * @var \Spryker\Zed\ZedNavigation\Business\Model\Extractor\PathExtractorInterface
     */
    protected $pathExtractor;

    /**
     * @var \Spryker\Zed\ZedNavigation\Business\Filter\NavigationItemFilterInterface
     */
    protected $navigationItemFilter;

    /**
     * @var \Spryker\Zed\ZedNavigation\ZedNavigationConfig
     */
    protected $zedNavigationConfig;

    /**
     * @param \Spryker\Zed\ZedNavigation\Business\Model\Collector\ZedNavigationCollectorInterface $navigationCollector
     * @param \Spryker\Zed\ZedNavigation\Business\Model\Formatter\MenuFormatterInterface $menuFormatter
     * @param \Spryker\Zed\ZedNavigation\Business\Model\Extractor\PathExtractorInterface $pathExtractor
     * @param \Spryker\Zed\ZedNavigation\Business\Filter\NavigationItemFilterInterface $navigationItemFilter
     * @param \Spryker\Zed\ZedNavigation\ZedNavigationConfig $zedNavigationConfig
     */
    public function __construct(
        ZedNavigationCollectorInterface $navigationCollector,
        MenuFormatterInterface $menuFormatter,
        PathExtractorInterface $pathExtractor,
        NavigationItemFilterInterface $navigationItemFilter,
        ZedNavigationConfig $zedNavigationConfig
    ) {
        $this->navigationCollector = $navigationCollector;
        $this->menuFormatter = $menuFormatter;
        $this->pathExtractor = $pathExtractor;
        $this->navigationItemFilter = $navigationItemFilter;
        $this->zedNavigationConfig = $zedNavigationConfig;
    }

    /**
     * @param string $pathInfo
     * @param string|null $navigationType
     *
     * @return array
     */
    public function build($pathInfo, ?string $navigationType = null)
    {
        if (!$navigationType) {
            $navigationType = $this->zedNavigationConfig->getDefaultNavigationType();
        }

        $navigationItems = $this->navigationCollector->getNavigation($navigationType);
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

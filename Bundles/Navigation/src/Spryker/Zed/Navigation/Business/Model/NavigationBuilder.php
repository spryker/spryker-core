<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Business\Model;

use Spryker\Zed\Navigation\Business\Model\Collector\NavigationCollectorInterface;
use Spryker\Zed\Navigation\Business\Model\Extractor\PathExtractorInterface;
use Spryker\Zed\Navigation\Business\Model\Formatter\MenuFormatterInterface;

class NavigationBuilder
{

    const MENU = 'menu';
    const PATH = 'path';

    /**
     * @var \Spryker\Zed\Navigation\Business\Model\Collector\NavigationCollectorInterface
     */
    private $navigationCollector;

    /**
     * @var \Spryker\Zed\Navigation\Business\Model\Formatter\MenuFormatterInterface
     */
    private $menuFormatter;

    /**
     * @var \Spryker\Zed\Navigation\Business\Model\Extractor\PathExtractorInterface
     */
    private $pathExtractor;

    /**
     * @param \Spryker\Zed\Navigation\Business\Model\Collector\NavigationCollectorInterface $navigationCollector
     * @param \Spryker\Zed\Navigation\Business\Model\Formatter\MenuFormatterInterface $menuFormatter
     * @param \Spryker\Zed\Navigation\Business\Model\Extractor\PathExtractorInterface $pathExtractor
     */
    public function __construct(
        NavigationCollectorInterface $navigationCollector,
        MenuFormatterInterface $menuFormatter,
        PathExtractorInterface $pathExtractor
    ) {
        $this->navigationCollector = $navigationCollector;
        $this->menuFormatter = $menuFormatter;
        $this->pathExtractor = $pathExtractor;
    }

    /**
     * @param string $pathInfo
     *
     * @return array
     */
    public function build($pathInfo)
    {
        $navigationPages = $this->navigationCollector->getNavigation();

        $menu = $this->menuFormatter->formatMenu($navigationPages, $pathInfo);
        $breadcrumb = $this->menuFormatter->formatMenu($navigationPages, $pathInfo, true);
        $path = $this->pathExtractor->extractPathFromMenu($breadcrumb);

        return [
            self::MENU => $menu,
            self::PATH => $path,
        ];
    }

}

<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Business\Model\Navigation;

use Spryker\Zed\Application\Business\Model\Navigation\Collector\NavigationCollectorInterface;
use Spryker\Zed\Application\Business\Model\Navigation\Extractor\PathExtractorInterface;
use Spryker\Zed\Application\Business\Model\Navigation\Formatter\MenuFormatterInterface;

class NavigationBuilder
{

    const MENU = 'menu';
    const PATH = 'path';

    /**
     * @var \Spryker\Zed\Application\Business\Model\Navigation\Collector\NavigationCollectorInterface
     */
    private $navigationCollector;

    /**
     * @var \Spryker\Zed\Application\Business\Model\Navigation\Formatter\MenuFormatterInterface
     */
    private $menuFormatter;

    /**
     * @param \Spryker\Zed\Application\Business\Model\Navigation\Collector\NavigationCollectorInterface $navigationCollector
     * @param \Spryker\Zed\Application\Business\Model\Navigation\Formatter\MenuFormatterInterface $menuFormatter
     * @param \Spryker\Zed\Application\Business\Model\Navigation\Extractor\PathExtractorInterface $pathExtractor
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

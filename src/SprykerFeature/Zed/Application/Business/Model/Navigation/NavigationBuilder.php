<?php

namespace SprykerFeature\Zed\Application\Business\Model\Navigation;

use SprykerFeature\Shared\Library\Bundle\BundleConfig;
use SprykerFeature\Zed\Application\Business\Model\Navigation\Collector\NavigationCollectorInterface;
use SprykerFeature\Zed\Application\Business\Model\Navigation\Extractor\PathExtractorInterface;
use SprykerFeature\Zed\Application\Business\Model\Navigation\Formatter\MenuFormatterInterface;

class NavigationBuilder
{
    const MENU = 'menu';
    const PATH = 'path';

    /**
     * @var BundleConfig
     */
    protected $bundleConfig;

    /**
     * @var NavigationCollectorInterface
     */
    protected $navigationCollector;

    /**
     * @var MenuFormatterInterface
     */
    protected $menuFormatter;

    /**
     * @param BundleConfig $bundleConfig
     * @param NavigationCollectorInterface $navigationCollector
     * @param MenuFormatterInterface $menuFormatter
     * @param PathExtractorInterface $pathExtractor
     */
    public function __construct(
        BundleConfig $bundleConfig,
        NavigationCollectorInterface $navigationCollector,
        MenuFormatterInterface $menuFormatter,
        PathExtractorInterface $pathExtractor
    ) {
        $this->bundleConfig = $bundleConfig;
        $this->navigationCollector = $navigationCollector;
        $this->menuFormatter = $menuFormatter;
        $this->pathExtractor = $pathExtractor;
    }

    /**
     * @param $pathInfo
     * @return array
     */
    public function build($pathInfo)
    {
        $navigationFiles = $this->bundleConfig->getActiveNavigations();
        $navigationPages = $this->navigationCollector->mergeNavigationFiles($navigationFiles);

        $menu = $this->menuFormatter->formatMenu($navigationPages, $pathInfo);
        $path = $this->pathExtractor->extractPathFromMenu($menu);

        return [
            self::MENU => $menu,
            self::PATH => $path
        ];
    }
}

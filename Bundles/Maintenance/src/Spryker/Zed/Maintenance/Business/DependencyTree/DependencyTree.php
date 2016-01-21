<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree;

use Symfony\Component\Finder\SplFileInfo;

class DependencyTree extends AbstractDependencyTree
{

    const META_FINDER = 'finder';
    const META_FILE = 'file';
    const META_FOREIGN_BUNDLE = 'foreign bundle';
    const META_FOREIGN_LAYER = 'foreign layer';
    const META_APPLICATION = 'application';
    const META_BUNDLE = 'bundle';
    const META_LAYER = 'layer';

    /**
     * @var FileInfoExtractor
     */
    private $fileInfoExtractor;

    /**
     * @param FileInfoExtractor $fileInfoExtractor
     */
    public function __construct(FileInfoExtractor $fileInfoExtractor)
    {
        $this->fileInfoExtractor = $fileInfoExtractor;
    }

    /**
     * @param SplFileInfo $fileInfo
     * @param string $to
     * @param array $meta
     *
     * @return void
     */
    public function addDependency(SplFileInfo $fileInfo, $to, array $meta = [])
    {
        $application = $this->fileInfoExtractor->getApplicationNameFromFileInfo($fileInfo);
        $bundle = $this->fileInfoExtractor->getBundleNameFromFileInfo($fileInfo);
        $layer = $this->fileInfoExtractor->getLayerNameFromFileInfo($fileInfo);

        if ($bundle === $to) {
            return;
        }

        $meta = $meta + [
            self::META_FILE => $fileInfo->getFilename(),
            self::META_FOREIGN_BUNDLE => $to,
            self::META_APPLICATION => $application,
            self::META_BUNDLE => $bundle,
            self::META_LAYER => $layer,
        ];

        if (!array_key_exists($application, $this->dependencyTree)) {
            $this->dependencyTree[$application] = [];
        }
        if (!array_key_exists($bundle, $this->dependencyTree[$application])) {
            $this->dependencyTree[$application][$bundle] = [];
        }
        if (!array_key_exists($to, $this->dependencyTree[$application][$bundle])) {
            $this->dependencyTree[$application][$bundle][$to] = [];
        }

        $this->dependencyTree[$application][$bundle][$to][] = $meta;
    }


}

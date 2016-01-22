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
    const META_FOREIGN_CLASS_NAME = 'foreign class name';
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
     * @param array $dependency
     *
     * @return void
     */
    public function addDependency(SplFileInfo $fileInfo, $to, array $dependency = [])
    {
        $application = $this->fileInfoExtractor->getApplicationNameFromFileInfo($fileInfo);
        $bundle = $this->fileInfoExtractor->getBundleNameFromFileInfo($fileInfo);
        $layer = $this->fileInfoExtractor->getLayerNameFromFileInfo($fileInfo);

        if ($bundle === $to) {
            return;
        }

        $dependency = $dependency + [
            self::META_FILE => $fileInfo->getFilename(),
            self::META_FOREIGN_BUNDLE => $to,
            self::META_APPLICATION => $application,
            self::META_BUNDLE => $bundle,
            self::META_LAYER => $layer,
        ];

        if (!array_key_exists($bundle, $this->dependencyTree)) {
            $this->dependencyTree[$bundle] = [];
        }

        if (!array_key_exists($to, $this->dependencyTree)) {
            $this->dependencyTree[$bundle][$to] = [];
        }

        $this->dependencyTree[$bundle][$to][] = $dependency;
    }


}

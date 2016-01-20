<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Development\Business\DependencyTree\DependencyReport;

use Spryker\Zed\Development\Business\DependencyTree\FileInfoExtractor;
use Symfony\Component\Finder\SplFileInfo;

class DependencyReport extends AbstractDependencyReport
{

    const META_FINDER = 'finder';
    const META_FILE = 'file';
    const META_DEPENDS = 'depends';
    const META_DEPENDS_LAYER = 'dependsLayer';
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
            self::META_DEPENDS => $to,
            self::META_APPLICATION => $application,
            self::META_BUNDLE => $bundle,
            self::META_LAYER => $layer,
        ];

        if (!array_key_exists($application, $this->dependencyReport)) {
            $this->dependencyReport[$application] = [];
        }
        if (!array_key_exists($bundle, $this->dependencyReport[$application])) {
            $this->dependencyReport[$application][$bundle] = [];
        }
        if (!array_key_exists($to, $this->dependencyReport[$application][$bundle])) {
            $this->dependencyReport[$application][$bundle][$to] = [];
        }

        $this->dependencyReport[$application][$bundle][$to][] = $meta;
    }


}

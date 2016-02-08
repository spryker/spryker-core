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
    const META_CLASS_NAME = 'class name';
    const META_FOREIGN_BUNDLE = 'foreign bundle';
    const META_FOREIGN_BUNDLE_IS_ENGINE = 'foreign bundle is engine';
    const META_FOREIGN_LAYER = 'foreign layer';
    const META_FOREIGN_CLASS_NAME = 'foreign class name';
    const META_FOREIGN_IS_EXTERNAL = 'foreign is external';
    const META_APPLICATION = 'application';
    const META_BUNDLE = 'bundle';
    const META_BUNDLE_IS_ENGINE = 'is engine';
    const META_LAYER = 'layer';

    /**
     * @var \Spryker\Zed\Maintenance\Business\DependencyTree\FileInfoExtractor
     */
    private $fileInfoExtractor;

    /**
     * @var array
     */
    private $engineBundles;

    /**
     * @param \Spryker\Zed\Maintenance\Business\DependencyTree\FileInfoExtractor $fileInfoExtractor
     * @param array $engineBundles
     */
    public function __construct(FileInfoExtractor $fileInfoExtractor, array $engineBundles)
    {
        $this->fileInfoExtractor = $fileInfoExtractor;
        $this->engineBundles = $engineBundles;
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $fileInfo
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
        $className = $this->fileInfoExtractor->getClassNameFromFile($fileInfo);

        if ($this->isSelfReference($bundle, $to)) {
            return;
        }

        $dependency = $dependency + [
            self::META_FILE => $fileInfo->getFilename(),
            self::META_CLASS_NAME => $className,
            self::META_FOREIGN_BUNDLE => $to,
            self::META_FOREIGN_BUNDLE_IS_ENGINE => $this->isEngineBundle($to),
            self::META_APPLICATION => $application,
            self::META_BUNDLE => $bundle,
            self::META_BUNDLE_IS_ENGINE => $this->isEngineBundle($bundle),
            self::META_LAYER => $layer,
        ];

        $this->dependencyTree[] = $dependency;
    }

    /**
     * @param string $bundle
     *
     * @return bool
     */
    private function isEngineBundle($bundle)
    {
        return (in_array($bundle, $this->engineBundles));
    }

    /**
     * @param string $bundle
     * @param string $to
     *
     * @return bool
     */
    private function isSelfReference($bundle, $to)
    {
        return ($bundle === $to);
    }

}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree;

use Symfony\Component\Finder\SplFileInfo;

class DependencyTree extends AbstractDependencyTree
{
    public const META_FINDER = 'finder';
    public const META_FILE = 'file';
    public const META_IN_TEST = 'in test';
    public const META_IS_OPTIONAL = 'is optional';
    public const META_ORGANIZATION = 'organization';
    public const META_CLASS_NAME = 'class name';
    public const META_FOREIGN_BUNDLE = 'foreign bundle';
    public const META_FOREIGN_BUNDLE_IS_ENGINE = 'foreign bundle is engine';
    public const META_FOREIGN_LAYER = 'foreign layer';
    public const META_FOREIGN_CLASS_NAME = 'foreign class name';
    public const META_FOREIGN_IS_EXTERNAL = 'foreign is external';
    public const META_APPLICATION = 'application';
    public const META_MODULE = 'bundle';
    public const META_MODULE_IS_ENGINE = 'is engine';
    public const META_LAYER = 'layer';
    public const META_COMPOSER_NAME = 'composer name';
    public const META_COMPOSER_VERSION = 'composer version';

    /**
     * @var \Spryker\Zed\Development\Business\DependencyTree\FileInfoExtractor
     */
    private $fileInfoExtractor;

    /**
     * @var array
     */
    private $engineBundles;

    /**
     * @param \Spryker\Zed\Development\Business\DependencyTree\FileInfoExtractor $fileInfoExtractor
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
        $organization = $this->fileInfoExtractor->getOrganizationFromFile($fileInfo);

        if ($this->isSelfReference($bundle, $to)) {
            return;
        }

        $dependency = $dependency + [
            static::META_FILE => $fileInfo->getFilename(),
            static::META_ORGANIZATION => $organization,
            static::META_CLASS_NAME => $className,
            static::META_FOREIGN_BUNDLE => $to,
            static::META_FOREIGN_BUNDLE_IS_ENGINE => $this->isEngineBundle($to),
            static::META_APPLICATION => $application,
            static::META_MODULE => $bundle,
            static::META_MODULE_IS_ENGINE => $this->isEngineBundle($bundle),
            static::META_LAYER => $layer,
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

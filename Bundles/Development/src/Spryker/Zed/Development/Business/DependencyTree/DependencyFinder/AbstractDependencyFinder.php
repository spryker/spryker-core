<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree\DependencyFinder;

use Spryker\Zed\Development\Business\DependencyTree\AbstractDependencyTree;
use Spryker\Zed\Development\Business\DependencyTree\DependencyTree;
use Spryker\Zed\Development\Business\DependencyTree\FileInfoExtractor;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\VarDumper\VarDumper;

abstract class AbstractDependencyFinder
{

    const DEPENDS_LAYER = 'dependsLayer';
    const LAYER_BUSINESS = 'Business';
    const LAYER_PERSISTENCE = 'Persistence';
    const LAYER_COMMUNICATION = 'Communication';

    /**
     * @var \Spryker\Zed\Development\Business\DependencyTree\AbstractDependencyTree
     */
    private $dependencyTree;

    /**
     * @var \Spryker\Zed\Development\Business\DependencyTree\FileInfoExtractor
     */
    private $fileInfoExtractor;

    /**
     * @param \Spryker\Zed\Development\Business\DependencyTree\AbstractDependencyTree $dependencyTree
     *
     * @return $this
     */
    public function setDependencyTree(AbstractDependencyTree $dependencyTree)
    {
        $this->dependencyTree = $dependencyTree;

        return $this;
    }

    /**
     * @return \Spryker\Zed\Development\Business\DependencyTree\AbstractDependencyTree
     */
    public function getDependencyTree()
    {
        return $this->dependencyTree;
    }

    /**
     * @param \Spryker\Zed\Development\Business\DependencyTree\FileInfoExtractor $fileInfoExtractor
     *
     * @return $this
     */
    public function setFileInfoExtractor(FileInfoExtractor $fileInfoExtractor)
    {
        $this->fileInfoExtractor = $fileInfoExtractor;

        return $this;
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $fileInfo
     *
     * @return void
     */
    abstract public function addDependencies(SplFileInfo $fileInfo);

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $fileInfo
     * @param string $to
     * @param array $dependencyInformation
     *
     * @return void
     */
    protected function addDependency(SplFileInfo $fileInfo, $to, array $dependencyInformation = [])
    {
        $dependencyInformation[DependencyTree::META_FINDER] = get_class($this);
        $dependencyInformation[DependencyTree::META_IN_TEST] = false;

        if (strpos($fileInfo->getPath(), '/tests/') !== false) {
            $dependencyInformation[DependencyTree::META_IN_TEST] = true;
        }

        if ($fileInfo->getExtension() !== 'php') {
            echo '<pre>' . PHP_EOL . VarDumper::dump($fileInfo) . PHP_EOL . 'Line: ' . __LINE__ . PHP_EOL . 'File: ' . __FILE__ . die();
        }

        $this->dependencyTree->addDependency($fileInfo, $to, $dependencyInformation);
    }

}

<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFinder;

use Spryker\Zed\Maintenance\Business\DependencyTree\AbstractDependencyTree;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTree;
use Spryker\Zed\Maintenance\Business\DependencyTree\FileInfoExtractor;
use Symfony\Component\Finder\SplFileInfo;

abstract class AbstractDependencyFinder
{

    const DEPENDS_LAYER = 'dependsLayer';
    const LAYER_BUSINESS = 'Business';
    const LAYER_PERSISTENCE = 'Persistence';
    const LAYER_COMMUNICATION = 'Communication';

    /**
     * @var \Spryker\Zed\Maintenance\Business\DependencyTree\AbstractDependencyTree
     */
    private $dependencyTree;

    /**
     * @var \Spryker\Zed\Maintenance\Business\DependencyTree\FileInfoExtractor
     */
    private $fileInfoExtractor;

    /**
     * @param \Spryker\Zed\Maintenance\Business\DependencyTree\AbstractDependencyTree $dependencyTree
     *
     * @return $this
     */
    public function setDependencyTree(AbstractDependencyTree $dependencyTree)
    {
        $this->dependencyTree = $dependencyTree;

        return $this;
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\DependencyTree\AbstractDependencyTree
     */
    public function getDependencyTree()
    {
        return $this->dependencyTree;
    }

    /**
     * @param \Spryker\Zed\Maintenance\Business\DependencyTree\FileInfoExtractor $fileInfoExtractor
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

        $this->dependencyTree->addDependency($fileInfo, $to, $dependencyInformation);
    }

}

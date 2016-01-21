<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFinder;

use Spryker\Zed\Maintenance\Business\DependencyTree\AbstractDependencyTree;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTree;
use Symfony\Component\Finder\SplFileInfo;

abstract class AbstractDependencyFinder
{

    const DEPENDS_LAYER = 'dependsLayer';
    const LAYER_BUSINESS = 'Business';
    const LAYER_PERSISTENCE = 'Persistence';
    const LAYER_COMMUNICATION = 'Communication';

    /**
     * @var string
     */
    private $bundle;

    /**
     * @var AbstractDependencyTree
     */
    private $report;

    /**
     * @param AbstractDependencyTree $dependencyTree
     *
     * @return $this
     */
    public function setDependencyTree(AbstractDependencyTree $dependencyTree)
    {
        $this->report = $dependencyTree;

        return $this;
    }

    /**
     * @return AbstractDependencyTree
     */
    public function getReport()
    {
        return $this->report;
    }

    /**
     * @param SplFileInfo $fileInfo
     *
     * @return void
     */
    abstract public function findDependencies(SplFileInfo $fileInfo);

    /**
     * @param SplFileInfo $fileInfo
     * @param string $to
     * @param array $meta
     *
     * @return void
     */
    protected function addDependency(SplFileInfo $fileInfo, $to, array $meta = [])
    {
        $meta[DependencyTree::META_FINDER] = get_class($this);

        $this->report->addDependency($fileInfo, $to, $meta);
    }
}

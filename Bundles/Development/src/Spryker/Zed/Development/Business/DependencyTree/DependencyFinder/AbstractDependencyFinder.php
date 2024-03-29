<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree\DependencyFinder;

use Spryker\Zed\Development\Business\DependencyTree\AbstractDependencyTree;
use Spryker\Zed\Development\Business\DependencyTree\DependencyTree;
use Symfony\Component\Finder\SplFileInfo;

abstract class AbstractDependencyFinder
{
    /**
     * @var string
     */
    public const DEPENDS_LAYER = 'dependsLayer';

    /**
     * @var string
     */
    public const LAYER_BUSINESS = 'Business';

    /**
     * @var string
     */
    public const LAYER_PERSISTENCE = 'Persistence';

    /**
     * @var string
     */
    public const LAYER_COMMUNICATION = 'Communication';

    /**
     * @var \Spryker\Zed\Development\Business\DependencyTree\AbstractDependencyTree
     */
    protected $dependencyTree;

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
        $dependencyInformation[DependencyTree::META_FINDER] = static::class;
        $dependencyInformation[DependencyTree::META_IN_TEST] = false;

        if (strpos($fileInfo->getPath(), '/tests/') !== false) {
            $dependencyInformation[DependencyTree::META_IN_TEST] = true;
        }

        if (!isset($dependencyInformation[DependencyTree::META_IS_OPTIONAL])) {
            $dependencyInformation[DependencyTree::META_IS_OPTIONAL] = false;
            if (strpos($fileInfo->getPath(), '/Plugin/') !== false) {
                $dependencyInformation[DependencyTree::META_IS_OPTIONAL] = true;
            }
        }

        $this->dependencyTree->addDependency($fileInfo, $to, $dependencyInformation);
    }
}

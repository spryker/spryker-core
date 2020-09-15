<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree;

use Spryker\Zed\Development\Business\DependencyTree\DependencyGraph\GraphBuilderInterface;

class DependencyGraphBuilder implements DependencyGraphBuilderInterface
{
    /**
     * @var \Spryker\Zed\Development\Business\DependencyTree\DependencyGraph\GraphBuilderInterface
     */
    protected $graphBuilder;

    /**
     * @var array
     */
    protected $dependencyTree;

    /**
     * @param \Spryker\Zed\Development\Business\DependencyTree\DependencyGraph\GraphBuilderInterface $graphBuilder
     * @param array $dependencyTree
     */
    public function __construct(
        GraphBuilderInterface $graphBuilder,
        array $dependencyTree
    ) {
        $this->graphBuilder = $graphBuilder;
        $this->dependencyTree = $dependencyTree;
    }

    /**
     * @return string
     */
    public function build()
    {
        return $this->graphBuilder->build($this->dependencyTree);
    }
}

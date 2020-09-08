<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree;

use Spryker\Zed\Development\Business\DependencyTree\DependencyTreeWriter\DependencyTreeWriterInterface;
use Spryker\Zed\Development\Business\DependencyTree\Finder\FinderInterface;

class DependencyTreeBuilder implements DependencyTreeBuilderInterface
{
    /**
     * @var \Spryker\Zed\Development\Business\DependencyTree\Finder\FinderInterface
     */
    protected $finder;

    /**
     * @var \Spryker\Zed\Development\Business\DependencyTree\AbstractDependencyTree
     */
    protected $dependencyTree;

    /**
     * @var \Spryker\Zed\Development\Business\DependencyTree\DependencyTreeWriter\DependencyTreeWriterInterface
     */
    protected $writer;

    /**
     * @var \Spryker\Zed\Development\Business\DependencyTree\DependencyFinder\AbstractDependencyFinder[]
     */
    protected $dependencyChecker = [];

    /**
     * @param \Spryker\Zed\Development\Business\DependencyTree\Finder\FinderInterface $finder
     * @param \Spryker\Zed\Development\Business\DependencyTree\AbstractDependencyTree $report
     * @param \Spryker\Zed\Development\Business\DependencyTree\DependencyTreeWriter\DependencyTreeWriterInterface $writer
     */
    public function __construct(FinderInterface $finder, AbstractDependencyTree $report, DependencyTreeWriterInterface $writer)
    {
        $this->finder = $finder;
        $this->dependencyTree = $report;
        $this->writer = $writer;
    }

    /**
     * @param \Spryker\Zed\Development\Business\DependencyTree\DependencyFinder\AbstractDependencyFinder|array $dependencyChecker
     *
     * @return $this
     */
    public function addDependencyChecker($dependencyChecker)
    {
        if (is_array($dependencyChecker)) {
            foreach ($dependencyChecker as $checker) {
                $this->addDependencyChecker($checker);
            }

            return $this;
        }

        $this->dependencyChecker[] = $dependencyChecker;

        return $this;
    }

    /**
     * @param string $module
     *
     * @return void
     */
    public function buildDependencyTree(string $module): void
    {
        foreach ($this->finder->find($module) as $fileInfo) {
            foreach ($this->dependencyChecker as $dependencyChecker) {
                $dependencyChecker->setDependencyTree($this->dependencyTree);
                $dependencyChecker->addDependencies($fileInfo);
            }
        }

        $this->writer->write($this->dependencyTree->getDependencyTree());
    }
}

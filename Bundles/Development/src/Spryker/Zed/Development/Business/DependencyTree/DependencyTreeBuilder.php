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
    private $finder;

    /**
     * @var \Spryker\Zed\Development\Business\DependencyTree\AbstractDependencyTree
     */
    private $dependencyTree;

    /**
     * @var \Spryker\Zed\Development\Business\DependencyTree\DependencyTreeWriter\DependencyTreeWriterInterface
     */
    private $writer;

    /**
     * @var \Spryker\Zed\Development\Business\DependencyTree\DependencyFinder\AbstractDependencyFinder[]
     */
    private $dependencyChecker = [];

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
     * @return array
     */
    public function buildDependencyTree(string $module): array
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

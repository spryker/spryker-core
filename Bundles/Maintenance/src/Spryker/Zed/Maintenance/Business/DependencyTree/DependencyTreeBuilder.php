<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree;

use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTreeWriter\DependencyTreeWriterInterface;

class DependencyTreeBuilder
{

    /**
     * @var \Spryker\Zed\Maintenance\Business\DependencyTree\Finder
     */
    private $finder;

    /**
     * @var \Spryker\Zed\Maintenance\Business\DependencyTree\AbstractDependencyTree
     */
    private $dependencyTree;

    /**
     * @var \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTreeWriter\DependencyTreeWriterInterface
     */
    private $writer;

    /**
     * @var \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFinder\AbstractDependencyFinder[]
     */
    private $dependencyChecker = [];

    /**
     * @param \Spryker\Zed\Maintenance\Business\DependencyTree\Finder $finder
     * @param \Spryker\Zed\Maintenance\Business\DependencyTree\AbstractDependencyTree $report
     * @param \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTreeWriter\DependencyTreeWriterInterface $writer
     */
    public function __construct(Finder $finder, AbstractDependencyTree $report, DependencyTreeWriterInterface $writer)
    {
        $this->finder = $finder;
        $this->dependencyTree = $report;
        $this->writer = $writer;
    }

    /**
     * @param \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFinder\AbstractDependencyFinder|array $dependencyChecker
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
     * @throws \Exception
     *
     * @return array
     */
    public function buildDependencyTree()
    {
        foreach ($this->finder->getFiles() as $fileInfo) {
            foreach ($this->dependencyChecker as $dependencyChecker) {
                $dependencyChecker->setDependencyTree($this->dependencyTree);
                $dependencyChecker->addDependencies($fileInfo);
            }
        }

        $this->writer->write($this->dependencyTree->getDependencyTree());
    }

}

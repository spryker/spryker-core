<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree;

use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFinder\AbstractDependencyFinder;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTreeWriter\DependencyTreeWriterInterface;

class DependencyTreeBuilder
{

    /**
     * @var Finder
     */
    private $finder;

    /**
     * @var AbstractDependencyTree
     */
    private $dependencyTree;

    /**
     * @var DependencyTreeWriterInterface
     */
    private $writer;

    /**
     * @var AbstractDependencyFinder[]
     */
    private $dependencyChecker = [];

    /**
     * @param Finder $finder
     * @param AbstractDependencyTree $report
     * @param DependencyTreeWriterInterface $writer
     */
    public function __construct(Finder $finder, AbstractDependencyTree $report, DependencyTreeWriterInterface $writer)
    {
        $this->finder = $finder;
        $this->dependencyTree = $report;
        $this->writer = $writer;
    }

    /**
     * @param AbstractDependencyFinder|array $dependencyChecker
     *
     * @return self
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
                $dependencyChecker->findDependencies($fileInfo);
            }
        }

        $this->writer->write($this->dependencyTree->getDependencyTree());
    }

}

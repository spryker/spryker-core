<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Zed\Development\Business\DependencyTree;

use Spryker\Zed\Development\Business\DependencyTree\DependencyFinder\AbstractDependencyFinder;
use Spryker\Zed\Development\Business\DependencyTree\DependencyReport\AbstractDependencyReport;
use Spryker\Zed\Development\Business\DependencyTree\DependencyTreeWriter\DependencyTreeWriterInterface;

class DependencyTreeBuilder
{

    /**
     * @var Finder
     */
    private $finder;

    /**
     * @var AbstractDependencyReport
     */
    private $report;

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
     * @param AbstractDependencyReport $report
     * @param DependencyTreeWriterInterface $writer
     */
    public function __construct(Finder $finder, AbstractDependencyReport $report, DependencyTreeWriterInterface $writer)
    {
        $this->finder = $finder;
        $this->report = $report;
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
                $dependencyChecker->setReport($this->report);
                $dependencyChecker->findDependencies($fileInfo);
            }
        }

        $this->writer->write($this->report->getTree());
    }

}

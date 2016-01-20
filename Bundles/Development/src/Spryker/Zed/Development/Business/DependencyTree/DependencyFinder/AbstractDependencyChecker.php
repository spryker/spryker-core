<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Development\Business\DependencyTree\DependencyFinder;

use Spryker\Zed\Development\Business\DependencyTree\DependencyReport\AbstractDependencyReport;
use Symfony\Component\Finder\SplFileInfo;

abstract class AbstractDependencyChecker
{

    /**
     * @var string
     */
    private $bundle;

    /**
     * @var AbstractDependencyReport
     */
    private $report;

    /**
     * @param AbstractDependencyReport $report
     *
     * @return $this
     */
    public function setReport(AbstractDependencyReport $report)
    {
        $this->report = $report;

        return $this;
    }

    /**
     * @return AbstractDependencyReport
     */
    public function getReport()
    {
        return $this->report;
    }

    /**
     * @param SplFileInfo $fileInfo
     * @param string $bundle
     *
     * @return void
     */
    abstract public function checkDependencies(SplFileInfo $fileInfo, $bundle);

    /**
     * @param $toBundle
     *
     * @return void
     */
    protected function addDependency($toBundle)
    {
        $this->bundle = $toBundle;
    }

    /**
     * @return bool
     */
    public function foundDependency()
    {
        return ($this->bundle !== null);
    }

    /**
     * @return string
     */
    public function getDependency()
    {
        $dependentBundle = $this->bundle;
        $this->bundle = null;

        return $dependentBundle;
    }
}

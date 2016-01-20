<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Development\Business\DependencyTree\DependencyFinder;

use Spryker\Zed\Development\Business\DependencyTree\DependencyReport\AbstractDependencyReport;
use Spryker\Zed\Development\Business\DependencyTree\DependencyReport\DependencyReport;
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
        $meta[DependencyReport::META_FINDER] = get_class($this);

        $this->report->addDependency($fileInfo, $to, $meta);
    }
}

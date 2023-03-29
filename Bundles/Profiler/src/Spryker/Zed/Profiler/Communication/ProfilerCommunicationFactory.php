<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Profiler\Communication;

use Spryker\Shared\Profiler\ProfilerCallTraceVisualizer\ProfilerCallTraceVisualizer;
use Spryker\Shared\Profiler\ProfilerCallTraceVisualizer\ProfilerCallTraceVisualizerInterface;
use Spryker\Shared\Profiler\ProfilerData\ProfilerDataStorageInterface;
use Spryker\Shared\Profiler\ProfilerData\ProfilerDataStorageSingleInstancePool;
use Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphModuleFilterCondition;
use Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeStorageFactory;
use Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeStorageFactoryInterface;
use Spryker\Shared\Profiler\ProfilerGraphDumper\ProfilerGraphDumperInterface;
use Spryker\Shared\Profiler\ProfilerGraphDumper\SvgProfilerGraphDumper;
use Spryker\Shared\Profiler\ProfilerGraphDumper\SvgProfilerModuleStyler;
use Spryker\Shared\Profiler\ProfilerGraphFactory\ProfilerGraphFactoryInterface;
use Spryker\Shared\Profiler\ProfilerGraphFactory\XhprofProfilerGraphFactory;
use Spryker\Shared\Profiler\WebProfiler\ProfilerDataCollector;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\Profiler\ProfilerConfig getConfig()
 */
class ProfilerCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Shared\Profiler\ProfilerData\ProfilerDataStorageInterface
     */
    public function createProfilerDataStorage(): ProfilerDataStorageInterface
    {
        return (new ProfilerDataStorageSingleInstancePool())->getProfilerDataStorage();
    }

    /**
     * @return \Spryker\Shared\Profiler\WebProfiler\ProfilerDataCollector
     */
    public function createProfilerDataCollector(): ProfilerDataCollector
    {
        return new ProfilerDataCollector($this->createProfilerDataStorage());
    }

    /**
     * @return \Spryker\Shared\Profiler\ProfilerCallTraceVisualizer\ProfilerCallTraceVisualizerInterface
     */
    public function createProfilerCallTraceVisualizer(): ProfilerCallTraceVisualizerInterface
    {
        return new ProfilerCallTraceVisualizer(
            $this->createProfilerGraphFactory(),
            $this->getProfilerGraphFilterConditions(),
            $this->createProfilerGraphDumper(),
        );
    }

    /**
     * @return \Spryker\Shared\Profiler\ProfilerGraphDumper\ProfilerGraphDumperInterface
     */
    protected function createProfilerGraphDumper(): ProfilerGraphDumperInterface
    {
        return new SvgProfilerGraphDumper($this->getSvgProfilerModuleStylers());
    }

    /**
     * @return array<\Spryker\Shared\Profiler\ProfilerGraphDumper\SvgProfilerModuleStyler>
     */
    protected function getSvgProfilerModuleStylers(): array
    {
        return [
            $this->createSvgProfilerModuleStyler(),
        ];
    }

    /**
     * @return \Spryker\Shared\Profiler\ProfilerGraphDumper\SvgProfilerModuleStyler
     */
    protected function createSvgProfilerModuleStyler(): SvgProfilerModuleStyler
    {
        return new SvgProfilerModuleStyler();
    }

    /**
     * @return array<\Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphFilterConditionInterface>
     */
    protected function getProfilerGraphFilterConditions(): array
    {
        return [
            $this->createProfilerGraphFilterCondition(),
        ];
    }

    /**
     * @return \Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphModuleFilterCondition
     */
    protected function createProfilerGraphFilterCondition(): ProfilerGraphModuleFilterCondition
    {
        return new ProfilerGraphModuleFilterCondition();
    }

    /**
     * @return \Spryker\Shared\Profiler\ProfilerGraphFactory\ProfilerGraphFactoryInterface
     */
    protected function createProfilerGraphFactory(): ProfilerGraphFactoryInterface
    {
        return new XhprofProfilerGraphFactory(
            $this->createProfilerGraphNodeStorageFactory(),
            $this->getConfig()->getMinNodeExecutionWallTimeInMicroSeconds(),
        );
    }

    /**
     * @return \Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeStorageFactoryInterface
     */
    protected function createProfilerGraphNodeStorageFactory(): ProfilerGraphNodeStorageFactoryInterface
    {
        return new ProfilerGraphNodeStorageFactory();
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Profiler\ProfilerCallTraceVisualizer;

use Generated\Shared\Transfer\ProfilerDataTransfer;
use Spryker\Shared\Profiler\ProfilerGraphDumper\ProfilerGraphDumperInterface;
use Spryker\Shared\Profiler\ProfilerGraphFactory\ProfilerGraphFactoryInterface;

class ProfilerCallTraceVisualizer implements ProfilerCallTraceVisualizerInterface
{
    /**
     * @var \Spryker\Shared\Profiler\ProfilerGraphFactory\ProfilerGraphFactoryInterface
     */
    protected ProfilerGraphFactoryInterface $profilerGraphFactory;

    /**
     * @var iterable<\Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphFilterConditionInterface>
     */
    protected iterable $profilerGraphFilerConditions;

    /**
     * @var \Spryker\Shared\Profiler\ProfilerGraphDumper\ProfilerGraphDumperInterface
     */
    protected ProfilerGraphDumperInterface $profilerGraphDumper;

    /**
     * @param \Spryker\Shared\Profiler\ProfilerGraphFactory\ProfilerGraphFactoryInterface $profilerGraphFactory
     * @param iterable<\Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphFilterConditionInterface> $profilerGraphFilerConditions
     * @param \Spryker\Shared\Profiler\ProfilerGraphDumper\ProfilerGraphDumperInterface $profilerGraphDumper
     */
    public function __construct(
        ProfilerGraphFactoryInterface $profilerGraphFactory,
        iterable $profilerGraphFilerConditions,
        ProfilerGraphDumperInterface $profilerGraphDumper
    ) {
        $this->profilerGraphFactory = $profilerGraphFactory;
        $this->profilerGraphFilerConditions = $profilerGraphFilerConditions;
        $this->profilerGraphDumper = $profilerGraphDumper;
    }

    /**
     * @param array<string, array<string, mixed>> $profilerCallTrace
     *
     * @return \Generated\Shared\Transfer\ProfilerDataTransfer
     */
    public function visualizeProfilerCallTrace(array $profilerCallTrace): ProfilerDataTransfer
    {
        $profilerGraph = $this->profilerGraphFactory->createByCallTrace($profilerCallTrace);

        foreach ($this->profilerGraphFilerConditions as $profilerGraphFilerCondition) {
            $profilerGraph->filterGraph($profilerGraphFilerCondition);
        }

        return $this->profilerGraphDumper->dump($profilerGraph);
    }
}

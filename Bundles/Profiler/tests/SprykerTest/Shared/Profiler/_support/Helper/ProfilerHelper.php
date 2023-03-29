<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Profiler\Helper;

use Codeception\Module;
use Spryker\Shared\Profiler\ProfilerCallTraceVisualizer\ProfilerCallTraceVisualizer;
use Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraph;
use Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphFilterConditionInterface;
use Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphInterface;
use Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphModuleFilterCondition;
use Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNode;
use Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeInterface;
use Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeStorage;
use Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeStorageFactory;
use Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeStorageInterface;
use Spryker\Shared\Profiler\ProfilerGraphDumper\SvgProfilerGraphDumper;
use Spryker\Shared\Profiler\ProfilerGraphDumper\SvgProfilerModuleStyler;
use Spryker\Shared\Profiler\ProfilerGraphFactory\XhprofProfilerGraphFactory;

class ProfilerHelper extends Module
{
    /**
     * @return \Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphInterface
     */
    public function createProfilerGraph(): ProfilerGraphInterface
    {
        return new ProfilerGraph($this->createProfilerGraphNodeStorage());
    }

    /**
     * @param callable $filterCallback
     *
     * @return \Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphFilterConditionInterface
     */
    public function getProfilerGraphNotBFilterCondition(callable $filterCallback): ProfilerGraphFilterConditionInterface
    {
        return new class ($filterCallback) implements ProfilerGraphFilterConditionInterface {
            /**
             * @var callable
             */
            private $filterCallback;

            /**
             * @param callable $filterCallback
             */
            public function __construct(callable $filterCallback)
            {
                $this->filterCallback = $filterCallback;
            }

            /**
             * @param \Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeInterface $node
             *
             * @return bool
             */
            public function isSatisfiedBy(ProfilerGraphNodeInterface $node): bool
            {
                return (bool)call_user_func($this->filterCallback, $node);
            }
        };
    }

    /**
     * @return \Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeStorageInterface
     */
    public function createProfilerGraphNodeStorage(): ProfilerGraphNodeStorageInterface
    {
        return new ProfilerGraphNodeStorage();
    }

    /**
     * @param string $nodeName
     *
     * @return \Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeInterface
     */
    public function createProfilerGraphNode(string $nodeName): ProfilerGraphNodeInterface
    {
        return new ProfilerGraphNode($nodeName);
    }

    /**
     * @param int $minNodeExecutionWallTime
     *
     * @return \Spryker\Shared\Profiler\ProfilerCallTraceVisualizer\ProfilerCallTraceVisualizer
     */
    public function createXhprofVisualizer(int $minNodeExecutionWallTime = 10000): ProfilerCallTraceVisualizer
    {
        $xhprofProfilerFactory = new XhprofProfilerGraphFactory(new ProfilerGraphNodeStorageFactory(), $minNodeExecutionWallTime);
        $xhprofProfilerGraphFilterConditions = [new ProfilerGraphModuleFilterCondition()];
        $xhprofProfilerDumper = new SvgProfilerGraphDumper([new SvgProfilerModuleStyler()]);

        return new ProfilerCallTraceVisualizer(
            $xhprofProfilerFactory,
            $xhprofProfilerGraphFilterConditions,
            $xhprofProfilerDumper,
        );
    }
}

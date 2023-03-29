<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Profiler\ProfilerGraphFactory;

use RuntimeException;
use Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraph;
use Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphInterface;
use Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeStorageFactoryInterface;

class XhprofProfilerGraphFactory implements ProfilerGraphFactoryInterface
{
    /**
     * @var int
     */
    protected const ROOT_EDGE_PARTS_COUNT = 1;

    /**
     * @var int
     */
    protected const NODES_EDGE_PARTS_COUNT = 2;

    /**
     * @var \Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeStorageFactoryInterface
     */
    protected ProfilerGraphNodeStorageFactoryInterface $profilerGraphNodeStorageFactory;

    /**
     * @var int
     */
    protected int $minNodeExecutionWallTimeInMicroSeconds;

    /**
     * @param \Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeStorageFactoryInterface $profilerGraphNodeStorageFactory
     * @param int $minNodeExecutionWallTimeInMicroSeconds
     */
    public function __construct(ProfilerGraphNodeStorageFactoryInterface $profilerGraphNodeStorageFactory, int $minNodeExecutionWallTimeInMicroSeconds)
    {
        $this->profilerGraphNodeStorageFactory = $profilerGraphNodeStorageFactory;
        $this->minNodeExecutionWallTimeInMicroSeconds = $minNodeExecutionWallTimeInMicroSeconds;
    }

    /**
     * @param array<string, array<string, mixed>> $callTrace
     *
     * @return \Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphInterface
     */
    public function createByCallTrace(array $callTrace): ProfilerGraphInterface
    {
        $profilerGraph = new ProfilerGraph($this->profilerGraphNodeStorageFactory->createNodeStorage());

        foreach ($callTrace as $graphEdge => $executeData) {
            if (isset($executeData['wt']) && $executeData['wt'] < $this->minNodeExecutionWallTimeInMicroSeconds) {
                continue;
            }

            $this->addGraphEdge($graphEdge, $profilerGraph);
        }

        return $profilerGraph;
    }

    /**
     * @param string $graphEdge
     * @param \Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraph $profilerGraph
     *
     * @return void
     */
    protected function addGraphEdge(string $graphEdge, ProfilerGraph $profilerGraph): void
    {
        [$fromNode, $toNode] = $this->getNodeNames($graphEdge);

        $profilerGraph->addGraphEdge($fromNode, $toNode);
    }

    /**
     * @param string $graphEdge
     *
     * @throws \RuntimeException
     *
     * @return array<string>
     */
    protected function getNodeNames(string $graphEdge): array
    {
        $graphEdgeParts = explode('==>', $graphEdge);

        $graphEdgePartsCount = count($graphEdgeParts);

        if (!in_array($graphEdgePartsCount, [static::ROOT_EDGE_PARTS_COUNT, static::NODES_EDGE_PARTS_COUNT], true)) {
            throw new RuntimeException(sprintf('Invalid xhprof node definition %s', $graphEdge));
        }

        return $graphEdgePartsCount === static::ROOT_EDGE_PARTS_COUNT
            ? [ProfilerGraphInterface::ROOT_NODE_NAME, $graphEdgeParts[0]]
            : [$graphEdgeParts[0], $graphEdgeParts[1]];
    }
}

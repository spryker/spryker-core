<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Profiler\ProfilerGraph;

interface ProfilerGraphInterface
{
    /**
     * @var string
     */
    public const ROOT_NODE_NAME = 'Request';

    /**
     * @param string $fromName
     * @param string $toName
     *
     * @return void
     */
    public function addGraphEdge(string $fromName, string $toName): void;

    /**
     * @param \Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphFilterConditionInterface $filter
     *
     * @return void
     */
    public function filterGraph(ProfilerGraphFilterConditionInterface $filter): void;

    /**
     * @param string $nodeName
     *
     * @return \Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeInterface|null
     */
    public function findNodeByName(string $nodeName): ?ProfilerGraphNodeInterface;
}

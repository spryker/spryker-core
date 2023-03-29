<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Profiler\ProfilerGraph;

interface ProfilerGraphNodeStorageInterface
{
    /**
     * @param string $name
     *
     * @return \Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeInterface|null
     */
    public function findNodeByName(string $name): ?ProfilerGraphNodeInterface;

    /**
     * @param \Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeInterface $node
     *
     * @return void
     */
    public function addNode(ProfilerGraphNodeInterface $node): void;

    /**
     * @param \Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeInterface $node
     *
     * @return void
     */
    public function removeNode(ProfilerGraphNodeInterface $node): void;

    /**
     * @return array<\Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeInterface>
     */
    public function getNodes(): array;
}

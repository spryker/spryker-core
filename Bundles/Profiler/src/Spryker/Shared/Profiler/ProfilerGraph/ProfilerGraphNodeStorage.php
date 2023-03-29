<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Profiler\ProfilerGraph;

class ProfilerGraphNodeStorage implements ProfilerGraphNodeStorageInterface
{
    /**
     * @var array<string, \Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeInterface>
     */
    protected array $nodes = [];

    /**
     * @param string $name
     *
     * @return \Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeInterface|null
     */
    public function findNodeByName(string $name): ?ProfilerGraphNodeInterface
    {
        return $this->nodes[$name] ?? null;
    }

    /**
     * @param \Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeInterface $node
     *
     * @return void
     */
    public function addNode(ProfilerGraphNodeInterface $node): void
    {
        if (isset($this->nodes[$node->getName()])) {
            return;
        }

        $this->nodes[$node->getName()] = $node;
    }

    /**
     * @param \Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeInterface $node
     *
     * @return void
     */
    public function removeNode(ProfilerGraphNodeInterface $node): void
    {
        unset($this->nodes[$node->getName()]);
    }

    /**
     * @return array<\Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeInterface>
     */
    public function getNodes(): array
    {
        return $this->nodes;
    }
}

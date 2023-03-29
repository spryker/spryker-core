<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Profiler\ProfilerGraph;

class ProfilerGraph implements ProfilerGraphInterface
{
    /**
     * @var \Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeStorageInterface
     */
    protected ProfilerGraphNodeStorageInterface $nodeStorage;

    /**
     * @param \Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeStorageInterface $profilerNodeStorage
     */
    public function __construct(ProfilerGraphNodeStorageInterface $profilerNodeStorage)
    {
        $this->nodeStorage = $profilerNodeStorage;
    }

    /**
     * @param string $fromName
     * @param string $toName
     *
     * @return void
     */
    public function addGraphEdge(string $fromName, string $toName): void
    {
        $fromNode = $this->nodeStorage->findNodeByName($fromName) ?? new ProfilerGraphNode($fromName);
        $toNode = $this->nodeStorage->findNodeByName($toName) ?? new ProfilerGraphNode($toName);

        $this->nodeStorage->addNode($fromNode);
        $this->nodeStorage->addNode($toNode);

        $fromNode->addToNode($toNode);
        $toNode->addFromNode($fromNode);
    }

    /**
     * @param \Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphFilterConditionInterface $filter
     *
     * @return void
     */
    public function filterGraph(ProfilerGraphFilterConditionInterface $filter): void
    {
        foreach ($this->nodeStorage->getNodes() as $name => $node) {
            if ($name === static::ROOT_NODE_NAME) {
                continue;
            }

            if ($filter->isSatisfiedBy($node)) {
                continue;
            }

            $node->remove();
            $this->nodeStorage->removeNode($node);
            unset($node);
        }
    }

    /**
     * @param string $nodeName
     *
     * @return \Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeInterface|null
     */
    public function findNodeByName(string $nodeName): ?ProfilerGraphNodeInterface
    {
        return $this->nodeStorage->findNodeByName($nodeName);
    }
}

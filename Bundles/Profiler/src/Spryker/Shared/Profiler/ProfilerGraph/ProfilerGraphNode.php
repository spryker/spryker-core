<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Profiler\ProfilerGraph;

class ProfilerGraphNode implements ProfilerGraphNodeInterface
{
    /**
     * @var string
     */
    protected string $name;

    /**
     * @var array<\Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeInterface>
     */
    protected array $fromNodes = [];

    /**
     * @var array<\Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeInterface>
     */
    protected array $toNodes = [];

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return void
     */
    public function remove(): void
    {
        foreach ($this->toNodes as $toNode) {
            $toNode->removeFromNode($this);
        }

        foreach ($this->fromNodes as $fromNode) {
            $fromNode->removeToNode($this);

            foreach ($this->toNodes as $toNode) {
                if ($this === $toNode) {
                    continue;
                }

                $fromNode->addToNode($toNode);
            }
        }
    }

    /**
     * @param \Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeInterface $fromNode
     *
     * @return void
     */
    public function addFromNode(ProfilerGraphNodeInterface $fromNode): void
    {
        if (in_array($fromNode, $this->fromNodes, true)) {
            return;
        }

        $this->fromNodes[] = $fromNode;

        $fromNode->addToNode($this);
    }

    /**
     * @param \Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeInterface $fromNode
     *
     * @return void
     */
    public function removeFromNode(ProfilerGraphNodeInterface $fromNode): void
    {
        $this->fromNodes = array_filter($this->fromNodes, static fn (ProfilerGraphNodeInterface $node): bool => $node !== $fromNode);
    }

    /**
     * @param \Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeInterface $toNode
     *
     * @return void
     */
    public function addToNode(ProfilerGraphNodeInterface $toNode): void
    {
        if (in_array($toNode, $this->toNodes, true)) {
            return;
        }

        $this->toNodes[] = $toNode;

        $toNode->addFromNode($this);
    }

    /**
     * @param \Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeInterface $toNode
     *
     * @return void
     */
    public function removeToNode(ProfilerGraphNodeInterface $toNode): void
    {
        $this->toNodes = array_filter($this->toNodes, static fn (ProfilerGraphNodeInterface $node) => $node !== $toNode);
    }

    /**
     * @return array<\Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeInterface>
     */
    public function getFromNodes(): array
    {
        return $this->fromNodes;
    }

    /**
     * @return array<\Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeInterface>
     */
    public function getToNodes(): array
    {
        return $this->toNodes;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->name;
    }
}

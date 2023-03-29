<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Profiler\ProfilerGraph;

interface ProfilerGraphNodeInterface
{
    /**
     * @return void
     */
    public function remove(): void;

    /**
     * @param \Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeInterface $fromNode
     *
     * @return void
     */
    public function addFromNode(ProfilerGraphNodeInterface $fromNode): void;

    /**
     * @param \Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeInterface $fromNode
     *
     * @return void
     */
    public function removeFromNode(ProfilerGraphNodeInterface $fromNode): void;

    /**
     * @param \Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeInterface $toNode
     *
     * @return void
     */
    public function addToNode(ProfilerGraphNodeInterface $toNode): void;

    /**
     * @param \Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeInterface $toNode
     *
     * @return void
     */
    public function removeToNode(ProfilerGraphNodeInterface $toNode): void;

    /**
     * @return array<\Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeInterface>
     */
    public function getFromNodes(): array;

    /**
     * @return array<\Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeInterface>
     */
    public function getToNodes(): array;

    /**
     * @return string
     */
    public function getName(): string;
}

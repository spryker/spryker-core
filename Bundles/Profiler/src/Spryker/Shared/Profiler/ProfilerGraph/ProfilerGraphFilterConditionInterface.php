<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Profiler\ProfilerGraph;

interface ProfilerGraphFilterConditionInterface
{
    /**
     * @param \Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeInterface $node
     *
     * @return bool
     */
    public function isSatisfiedBy(ProfilerGraphNodeInterface $node): bool;
}

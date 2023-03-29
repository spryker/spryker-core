<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Profiler\ProfilerGraphDumper;

use Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeInterface;

interface ProfilerGraphNodeStylerInterface
{
    /**
     * @param \Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeInterface $node
     *
     * @return string
     */
    public function apply(ProfilerGraphNodeInterface $node): string;
}

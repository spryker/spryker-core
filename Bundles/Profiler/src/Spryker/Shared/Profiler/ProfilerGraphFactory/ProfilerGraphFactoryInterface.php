<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Profiler\ProfilerGraphFactory;

use Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphInterface;

interface ProfilerGraphFactoryInterface
{
    /**
     * @param array<string, array<string, mixed>> $callTrace
     *
     * @return \Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphInterface
     */
    public function createByCallTrace(array $callTrace): ProfilerGraphInterface;
}

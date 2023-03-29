<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Profiler\ProfilerCallTraceVisualizer;

use Generated\Shared\Transfer\ProfilerDataTransfer;

interface ProfilerCallTraceVisualizerInterface
{
    /**
     * @param array<string, array<string, mixed>> $profilerCallTrace
     *
     * @return \Generated\Shared\Transfer\ProfilerDataTransfer
     */
    public function visualizeProfilerCallTrace(array $profilerCallTrace): ProfilerDataTransfer;
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Profiler\ProfilerGraphDumper;

use Generated\Shared\Transfer\ProfilerDataTransfer;
use Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphInterface;

interface ProfilerGraphDumperInterface
{
    /**
     * @param \Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphInterface $profilerGraph
     *
     * @return \Generated\Shared\Transfer\ProfilerDataTransfer
     */
    public function dump(ProfilerGraphInterface $profilerGraph): ProfilerDataTransfer;
}

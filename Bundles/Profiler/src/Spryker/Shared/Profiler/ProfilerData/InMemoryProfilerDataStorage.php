<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Profiler\ProfilerData;

use Generated\Shared\Transfer\ProfilerDataTransfer;

class InMemoryProfilerDataStorage implements ProfilerDataStorageInterface
{
    /**
     * @var \Generated\Shared\Transfer\ProfilerDataTransfer|null
     */
    protected ?ProfilerDataTransfer $profilerData = null;

    /**
     * @param \Generated\Shared\Transfer\ProfilerDataTransfer $profilerDumpData
     *
     * @return void
     */
    public function logProfilerData(ProfilerDataTransfer $profilerDumpData): void
    {
        $this->profilerData = $profilerDumpData;
    }

    /**
     * @return \Generated\Shared\Transfer\ProfilerDataTransfer|null
     */
    public function getProfilerData(): ?ProfilerDataTransfer
    {
        return $this->profilerData;
    }
}

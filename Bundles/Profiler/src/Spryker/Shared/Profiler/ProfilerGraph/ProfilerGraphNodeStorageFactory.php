<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Profiler\ProfilerGraph;

class ProfilerGraphNodeStorageFactory implements ProfilerGraphNodeStorageFactoryInterface
{
    /**
     * @return \Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeStorageInterface
     */
    public function createNodeStorage(): ProfilerGraphNodeStorageInterface
    {
        return new ProfilerGraphNodeStorage();
    }
}

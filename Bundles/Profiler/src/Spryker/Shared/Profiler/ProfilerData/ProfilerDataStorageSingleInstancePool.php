<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Profiler\ProfilerData;

class ProfilerDataStorageSingleInstancePool implements ProfilerDataStorageSingleInstancePoolInterface
{
    /**
     * @var \Spryker\Shared\Profiler\ProfilerData\ProfilerDataStorageInterface
     */
    protected static ?ProfilerDataStorageInterface $storage = null;

    /**
     * @return \Spryker\Shared\Profiler\ProfilerData\ProfilerDataStorageInterface
     */
    public function getProfilerDataStorage(): ProfilerDataStorageInterface
    {
        if (static::$storage === null) {
            static::$storage = new InMemoryProfilerDataStorage();
        }

        return static::$storage;
    }
}

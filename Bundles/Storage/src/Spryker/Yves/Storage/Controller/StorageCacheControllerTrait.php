<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Storage\Controller;

use Spryker\Shared\Storage\StorageConstants;

/**
 * @method \Spryker\Yves\Kernel\Application getApplication()
 */
trait StorageCacheControllerTrait
{
    /**
     * @param string $storageCacheStrategy
     *
     * @return void
     */
    protected function initializeStorageCacheStrategy($storageCacheStrategy)
    {
        if (!$this->getApplication()->has(StorageConstants::STORAGE_CACHE_STRATEGY)) {
            $application = $this->getApplication();
            $application->set(StorageConstants::STORAGE_CACHE_STRATEGY, $storageCacheStrategy);
            $application->configure(StorageConstants::STORAGE_CACHE_STRATEGY, ['isGlobal' => true]);
        }
    }
}

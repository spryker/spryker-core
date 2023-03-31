<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreStorage;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class StoreStorageConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return string|null
     */
    public function getStoreSynchronizationPoolName(): ?string
    {
        return null;
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getStoreCreationResourcesToReSync(): array
    {
        return [];
    }
}

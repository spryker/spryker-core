<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreStorage\Dependency\Facade;

interface StoreStorageToSynchronizationFacadeInterface
{
    /**
     * @param array<string> $resources
     * @param array<int> $ids
     *
     * @return void
     */
    public function executeResolvedPluginsBySourcesWithIds(array $resources, array $ids): void;
}

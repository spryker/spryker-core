<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreStorage\Dependency\Facade;

class StoreStorageToSynchronizationFacadeBridge implements StoreStorageToSynchronizationFacadeInterface
{
    /**
     * @var \Spryker\Zed\Synchronization\Business\SynchronizationFacadeInterface
     */
    protected $synchronizationFacade;

    /**
     * @param \Spryker\Zed\Synchronization\Business\SynchronizationFacadeInterface $synchronizationFacade
     */
    public function __construct($synchronizationFacade)
    {
        $this->synchronizationFacade = $synchronizationFacade;
    }

    /**
     * @param array<string> $resources
     * @param array<int> $ids
     *
     * @return void
     */
    public function executeResolvedPluginsBySourcesWithIds(array $resources, array $ids): void
    {
        $this->synchronizationFacade->executeResolvedPluginsBySourcesWithIds($resources, $ids);
    }
}

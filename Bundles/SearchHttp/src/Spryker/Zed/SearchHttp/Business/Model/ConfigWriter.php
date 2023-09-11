<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchHttp\Business\Model;

use Generated\Shared\Transfer\SearchHttpConfigTransfer;
use Spryker\Zed\SearchHttp\Dependency\Facade\SearchHttpToStoreFacadeInterface;
use Spryker\Zed\SearchHttp\Persistence\SearchHttpEntityManagerInterface;

class ConfigWriter implements ConfigWriterInterface
{
    /**
     * @var \Spryker\Zed\SearchHttp\Persistence\SearchHttpEntityManagerInterface
     */
    protected SearchHttpEntityManagerInterface $searchHttpEntityManager;

    /**
     * @var \Spryker\Zed\SearchHttp\Dependency\Facade\SearchHttpToStoreFacadeInterface
     */
    protected SearchHttpToStoreFacadeInterface $storeFacade;

    /**
     * @param \Spryker\Zed\SearchHttp\Persistence\SearchHttpEntityManagerInterface $searchHttpEntityManager
     * @param \Spryker\Zed\SearchHttp\Dependency\Facade\SearchHttpToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        SearchHttpEntityManagerInterface $searchHttpEntityManager,
        SearchHttpToStoreFacadeInterface $storeFacade
    ) {
        $this->searchHttpEntityManager = $searchHttpEntityManager;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\SearchHttp\Business\Model\ConfigWriter::saveSearchHttpConfig()} instead.
     *
     * @param \Generated\Shared\Transfer\SearchHttpConfigTransfer $searchHttpConfigTransfer
     * @param string $storeReference
     *
     * @return void
     */
    public function write(SearchHttpConfigTransfer $searchHttpConfigTransfer, string $storeReference): void
    {
        $storeTransfer = $this->storeFacade->getStoreByStoreReference($storeReference);

        $this->searchHttpEntityManager->saveSearchHttpConfig($searchHttpConfigTransfer, $storeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SearchHttpConfigTransfer $searchHttpConfigTransfer
     *
     * @return void
     */
    public function saveSearchHttpConfig(SearchHttpConfigTransfer $searchHttpConfigTransfer): void
    {
        $storeTransfers = $this->storeFacade->getAllStores();

        foreach ($storeTransfers as $storeTransfer) {
            $this->searchHttpEntityManager->saveSearchHttpConfig($searchHttpConfigTransfer, $storeTransfer);
        }
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleStorage\Business\Writer;

use Spryker\Zed\ProductBundleStorage\Dependency\Facade\ProductBundleStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductBundleStorage\Dependency\Facade\ProductBundleStorageToProductBundleFacadeInterface;
use Spryker\Zed\ProductBundleStorage\Persistence\ProductBundleStorageEntityManagerInterface;
use Spryker\Zed\ProductBundleStorage\Persistence\ProductBundleStorageRepositoryInterface;

class ProductBundleStorageWriter implements ProductBundleStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductBundleStorage\Dependency\Facade\ProductBundleStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\ProductBundleStorage\Dependency\Facade\ProductBundleStorageToProductBundleFacadeInterface
     */
    protected $productBundleFacade;

    /**
     * @var \Spryker\Zed\ProductBundleStorage\Persistence\ProductBundleStorageEntityManagerInterface
     */
    protected $productBundleStorageEntityManager;

    /**
     * @var \Spryker\Zed\ProductBundleStorage\Persistence\ProductBundleStorageRepositoryInterface
     */
    protected $productBundleStorageRepository;

    /**
     * @param \Spryker\Zed\ProductBundleStorage\Dependency\Facade\ProductBundleStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\ProductBundleStorage\Dependency\Facade\ProductBundleStorageToProductBundleFacadeInterface $productBundleFacade
     * @param \Spryker\Zed\ProductBundleStorage\Persistence\ProductBundleStorageEntityManagerInterface $productBundleStorageEntityManager
     * @param \Spryker\Zed\ProductBundleStorage\Persistence\ProductBundleStorageRepositoryInterface $productBundleStorageRepository
     */
    public function __construct(
        ProductBundleStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        ProductBundleStorageToProductBundleFacadeInterface $productBundleFacade,
        ProductBundleStorageEntityManagerInterface $productBundleStorageEntityManager,
        ProductBundleStorageRepositoryInterface $productBundleStorageRepository
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->productBundleFacade = $productBundleFacade;
        $this->productBundleStorageEntityManager = $productBundleStorageEntityManager;
        $this->productBundleStorageRepository = $productBundleStorageRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductBundleEvents(array $eventTransfers): void
    {
        // TODO

        return;
    }
}

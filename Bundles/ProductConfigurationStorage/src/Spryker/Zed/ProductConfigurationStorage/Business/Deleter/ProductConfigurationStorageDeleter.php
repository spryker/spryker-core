<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationStorage\Business\Deleter;

use Spryker\Zed\ProductConfigurationStorage\Dependency\Facade\ProductConfigurationStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductConfigurationStorage\Persistence\ProductConfigurationStorageEntityManagerInterface;
use Spryker\Zed\ProductConfigurationStorage\Persistence\ProductConfigurationStorageRepositoryInterface;

class ProductConfigurationStorageDeleter implements ProductConfigurationStorageDeleterInterface
{
    /**
     * @uses SpyProductConfigurationTableMap::COL_FK_PRODUCT
     */
    protected const COL_FK_PRODUCT = 'spy_product_configuration.fk_product';

    /**
     * @var \Spryker\Zed\ProductConfigurationStorage\Dependency\Facade\ProductConfigurationStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\ProductConfigurationStorage\Dependency\Facade\ProductConfigurationStorageToProductConfigurationFacadeInterface
     */
    protected $configurationFacade;

    /**
     * @var \Spryker\Zed\ProductConfigurationStorage\Persistence\ProductConfigurationStorageRepositoryInterface
     */
    protected $configurationStorageRepository;

    /**
     * @var \Spryker\Zed\ProductConfigurationStorage\Persistence\ProductConfigurationStorageEntityManagerInterface
     */
    protected $productConfigurationStorageEntityManager;

    /**
     * @param \Spryker\Zed\ProductConfigurationStorage\Dependency\Facade\ProductConfigurationStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\ProductConfigurationStorage\Persistence\ProductConfigurationStorageEntityManagerInterface $productConfigurationStorageEntityManager
     * @param \Spryker\Zed\ProductConfigurationStorage\Persistence\ProductConfigurationStorageRepositoryInterface $configurationStorageRepository
     */
    public function __construct(
        ProductConfigurationStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        ProductConfigurationStorageEntityManagerInterface $productConfigurationStorageEntityManager,
        ProductConfigurationStorageRepositoryInterface $configurationStorageRepository
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->configurationStorageRepository = $configurationStorageRepository;
        $this->productConfigurationStorageEntityManager = $productConfigurationStorageEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function deleteCollectionByProductConfigurationStorageEvents(array $eventTransfers): void
    {
        $productConfigurationIds = $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);

        $this->productConfigurationStorageEntityManager->deleteProductConfigurationStorageByFkProductConfiguration($productConfigurationIds);
    }
}

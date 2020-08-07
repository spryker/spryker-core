<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationStorage\Business\Deleter;

use Spryker\Zed\ProductConfigurationStorage\Dependency\Facade\ProductConfigurationStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductConfigurationStorage\Persistence\ProductConfigurationStorageEntityManagerInterface;

class ProductConfigurationStorageDeleter implements ProductConfigurationStorageDeleterInterface
{
    /**
     * @uses \Orm\Zed\ConfigurableBundle\Persistence\Map\SpyProductConfigurationTableMap::COL_FK_PRODUCT
     */
    protected const COL_FK_PRODUCT = 'spy_product_configuration.fk_product';

    /**
     * @var \Spryker\Zed\ProductConfigurationStorage\Dependency\Facade\ProductConfigurationStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\ProductConfigurationStorage\Persistence\ProductConfigurationStorageEntityManagerInterface
     */
    protected $productConfigurationStorageEntityManager;

    /**
     * @param \Spryker\Zed\ProductConfigurationStorage\Dependency\Facade\ProductConfigurationStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\ProductConfigurationStorage\Persistence\ProductConfigurationStorageEntityManagerInterface $productConfigurationStorageEntityManager
     */
    public function __construct(
        ProductConfigurationStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        ProductConfigurationStorageEntityManagerInterface $productConfigurationStorageEntityManager
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
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

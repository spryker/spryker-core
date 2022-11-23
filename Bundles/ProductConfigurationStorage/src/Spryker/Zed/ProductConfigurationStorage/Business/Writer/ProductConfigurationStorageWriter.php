<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationStorage\Business\Writer;

use Generated\Shared\Transfer\ProductConfigurationCollectionTransfer;
use Generated\Shared\Transfer\ProductConfigurationConditionsTransfer;
use Generated\Shared\Transfer\ProductConfigurationCriteriaTransfer;
use Generated\Shared\Transfer\ProductConfigurationStorageTransfer;
use Spryker\Zed\ProductConfigurationStorage\Business\Mapper\ProductConfigurationStorageMapperInterface;
use Spryker\Zed\ProductConfigurationStorage\Dependency\Facade\ProductConfigurationStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductConfigurationStorage\Dependency\Facade\ProductConfigurationStorageToProductConfigurationFacadeInterface;
use Spryker\Zed\ProductConfigurationStorage\Persistence\ProductConfigurationStorageEntityManagerInterface;

class ProductConfigurationStorageWriter implements ProductConfigurationStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductConfigurationStorage\Dependency\Facade\ProductConfigurationStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\ProductConfigurationStorage\Dependency\Facade\ProductConfigurationStorageToProductConfigurationFacadeInterface
     */
    protected $configurationFacade;

    /**
     * @var \Spryker\Zed\ProductConfigurationStorage\Persistence\ProductConfigurationStorageEntityManagerInterface
     */
    protected $productConfigurationStorageEntityManager;

    /**
     * @var \Spryker\Zed\ProductConfigurationStorage\Business\Mapper\ProductConfigurationStorageMapperInterface
     */
    protected $productConfigurationStorageMapper;

    /**
     * @param \Spryker\Zed\ProductConfigurationStorage\Dependency\Facade\ProductConfigurationStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\ProductConfigurationStorage\Dependency\Facade\ProductConfigurationStorageToProductConfigurationFacadeInterface $configurationFacade
     * @param \Spryker\Zed\ProductConfigurationStorage\Business\Mapper\ProductConfigurationStorageMapperInterface $productConfigurationStorageMapper
     * @param \Spryker\Zed\ProductConfigurationStorage\Persistence\ProductConfigurationStorageEntityManagerInterface $productConfigurationStorageEntityManager
     */
    public function __construct(
        ProductConfigurationStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        ProductConfigurationStorageToProductConfigurationFacadeInterface $configurationFacade,
        ProductConfigurationStorageMapperInterface $productConfigurationStorageMapper,
        ProductConfigurationStorageEntityManagerInterface $productConfigurationStorageEntityManager
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->configurationFacade = $configurationFacade;
        $this->productConfigurationStorageEntityManager = $productConfigurationStorageEntityManager;
        $this->productConfigurationStorageMapper = $productConfigurationStorageMapper;
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductConfigurationEvents(array $eventTransfers): void
    {
        $productConfigurationIds = $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);

        if (!$productConfigurationIds) {
            return;
        }

        $productConfigurationCollectionTransfer = $this->getProductConfigurationCollection($productConfigurationIds);

        foreach ($productConfigurationCollectionTransfer->getProductConfigurations() as $productConfigurationTransfer) {
            $productConfigurationStorageTransfer = $this->productConfigurationStorageMapper
                ->mapProductConfigurationTransferToProductConfigurationStorageTransfer(
                    $productConfigurationTransfer,
                    new ProductConfigurationStorageTransfer(),
                );

            $this->productConfigurationStorageEntityManager->saveProductConfigurationStorage(
                $productConfigurationStorageTransfer,
            );
        }
    }

    /**
     * @param array<int> $productConfigurationIds
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationCollectionTransfer
     */
    protected function getProductConfigurationCollection(array $productConfigurationIds): ProductConfigurationCollectionTransfer
    {
        $productConfigurationConditionsTransfer = (new ProductConfigurationConditionsTransfer())->setProductConfigurationIds($productConfigurationIds);
        $productConfigurationCriteria = (new ProductConfigurationCriteriaTransfer())->setProductConfigurationConditions($productConfigurationConditionsTransfer);

        return $this->configurationFacade->getProductConfigurationCollection($productConfigurationCriteria);
    }
}

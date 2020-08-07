<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationStorage\Business;

use Generated\Shared\Transfer\ProductConfigurationCollectionTransfer;
use Generated\Shared\Transfer\ProductConfigurationFilterTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductConfigurationStorage\Business\ProductConfigurationStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductConfigurationStorage\Persistence\ProductConfigurationStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductConfigurationStorage\Persistence\ProductConfigurationStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductConfigurationStorage\ProductConfigurationStorageConfig getConfig()
 */
class ProductConfigurationStorageFacade extends AbstractFacade implements ProductConfigurationStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeProductConfigurationStorageCollectionByProductConfigurationEvents(array $eventTransfers): void
    {
        $this->getFactory()->createProductConfigurationStorageWriter()
            ->writeCollectionByProductConfigurationStorageEvents($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function deleteProductConfigurationStorageCollection(array $eventTransfers): void
    {
        $this->getFactory()->createProductConfigurationStorageDeleter()
            ->deleteCollectionByProductConfigurationStorageEvents($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfigurationFilterTransfer $productConfigurationFilterTransfer
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function findProductConfigurationStorageDataTransfersByIds(
        ProductConfigurationFilterTransfer $productConfigurationFilterTransfer
    ): array {
        return $this->getRepository()->findProductConfigurationStorageDataTransfersByIds($productConfigurationFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfigurationFilterTransfer $productConfigurationFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationCollectionTransfer
     */
    public function getProductConfigurationCollection(
        ProductConfigurationFilterTransfer $productConfigurationFilterTransfer
    ): ProductConfigurationCollectionTransfer {
        return $this->getFactory()->getProductConfigurationFacade()
            ->getProductConfigurationCollection($productConfigurationFilterTransfer);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationStorage\Business;

use Generated\Shared\Transfer\FilterTransfer;
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
    public function writeCollectionByProductConfigurationEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createProductConfigurationStorageWriter()
            ->writeCollectionByProductConfigurationEvents($eventTransfers);
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
    public function deleteCollectionByProductConfigurationEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createProductConfigurationStorageDeleter()
            ->deleteCollectionByProductConfigurationEvents($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array $productConfigurationStorageIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getFilteredProductConfigurationStorageDataTransfers(
        FilterTransfer $filterTransfer,
        array $productConfigurationStorageIds
    ): array {
        return $this->getRepository()->getFilteredProductConfigurationStorageDataTransfers(
            $filterTransfer,
            $productConfigurationStorageIds
        );
    }
}

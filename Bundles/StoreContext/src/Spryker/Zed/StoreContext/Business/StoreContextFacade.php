<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContext\Business;

use Generated\Shared\Transfer\StoreCollectionTransfer;
use Generated\Shared\Transfer\StoreContextCollectionRequestTransfer;
use Generated\Shared\Transfer\StoreContextCollectionResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\StoreContext\Business\StoreContextBusinessFactory getFactory()
 * @method \Spryker\Zed\StoreContext\Persistence\StoreContextRepositoryInterface getRepository()
 * @method \Spryker\Zed\StoreContext\Persistence\StoreContextEntityManagerInterface getEntityManager()
 */
class StoreContextFacade extends AbstractFacade implements StoreContextFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreCollectionTransfer $storeCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\StoreCollectionTransfer
     */
    public function expandStoreCollection(StoreCollectionTransfer $storeCollectionTransfer): StoreCollectionTransfer
    {
        return $this->getFactory()
            ->createStoreExpander()
            ->expandStoreCollectionTransferWithStoreContext($storeCollectionTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreContextCollectionRequestTransfer $storeContextCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\StoreContextCollectionResponseTransfer
     */
    public function validateStoreContextCollection(
        StoreContextCollectionRequestTransfer $storeContextCollectionRequestTransfer
    ): StoreContextCollectionResponseTransfer {
        return $this->getFactory()
            ->createStoreContextValidator()
            ->validateStoreContextCollection($storeContextCollectionRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreContextCollectionRequestTransfer $storeContextCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\StoreContextCollectionResponseTransfer
     */
    public function createStoreContextCollection(
        StoreContextCollectionRequestTransfer $storeContextCollectionRequestTransfer
    ): StoreContextCollectionResponseTransfer {
        return $this->getFactory()
            ->createStoreContextCreator()
            ->createStoreContextCollection($storeContextCollectionRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreContextCollectionRequestTransfer $storeCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\StoreContextCollectionResponseTransfer
     */
    public function updateStoreContextCollection(StoreContextCollectionRequestTransfer $storeCollectionRequestTransfer): StoreContextCollectionResponseTransfer
    {
        return $this->getFactory()
            ->createStoreContextUpdater()
            ->updateStoreContextCollection($storeCollectionRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<string>
     */
    public function getAvilableTimeZones(): array
    {
        return $this->getFactory()
            ->createTimezoneReader()
            ->getAvailableTimezones();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<string>
     */
    public function getAvilableApplications(): array
    {
        return $this->getFactory()
            ->getConfig()
            ->getStoreContextApplications();
    }
}

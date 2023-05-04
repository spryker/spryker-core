<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business;

use Generated\Shared\Transfer\ServicePointAddressCollectionRequestTransfer;
use Generated\Shared\Transfer\ServicePointAddressCollectionResponseTransfer;
use Generated\Shared\Transfer\ServicePointAddressCollectionTransfer;
use Generated\Shared\Transfer\ServicePointAddressCriteriaTransfer;
use Generated\Shared\Transfer\ServicePointCollectionRequestTransfer;
use Generated\Shared\Transfer\ServicePointCollectionResponseTransfer;
use Generated\Shared\Transfer\ServicePointCollectionTransfer;
use Generated\Shared\Transfer\ServicePointCriteriaTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ServicePoint\Business\ServicePointBusinessFactory getFactory()
 * @method \Spryker\Zed\ServicePoint\Persistence\ServicePointRepositoryInterface getRepository()
 * @method \Spryker\Zed\ServicePoint\Persistence\ServicePointEntityManagerInterface getEntityManager()
 */
class ServicePointFacade extends AbstractFacade implements ServicePointFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ServicePointCriteriaTransfer $servicePointCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointCollectionTransfer
     */
    public function getServicePointCollection(
        ServicePointCriteriaTransfer $servicePointCriteriaTransfer
    ): ServicePointCollectionTransfer {
        return $this->getFactory()
            ->createServicePointReader()
            ->getServicePointCollection($servicePointCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ServicePointCollectionRequestTransfer $servicePointCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointCollectionResponseTransfer
     */
    public function createServicePointCollection(
        ServicePointCollectionRequestTransfer $servicePointCollectionRequestTransfer
    ): ServicePointCollectionResponseTransfer {
        return $this->getFactory()
            ->createServicePointCreator()
            ->createServicePointCollection($servicePointCollectionRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ServicePointCollectionRequestTransfer $servicePointCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointCollectionResponseTransfer
     */
    public function updateServicePointCollection(
        ServicePointCollectionRequestTransfer $servicePointCollectionRequestTransfer
    ): ServicePointCollectionResponseTransfer {
        return $this->getFactory()
            ->createServicePointUpdater()
            ->updateServicePointCollection($servicePointCollectionRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ServicePointAddressCriteriaTransfer $servicePointAddressCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointAddressCollectionTransfer
     */
    public function getServicePointAddressCollection(
        ServicePointAddressCriteriaTransfer $servicePointAddressCriteriaTransfer
    ): ServicePointAddressCollectionTransfer {
        return $this->getRepository()->getServicePointAddressCollection($servicePointAddressCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ServicePointAddressCollectionRequestTransfer $servicePointAddressCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointAddressCollectionResponseTransfer
     */
    public function createServicePointAddressCollection(
        ServicePointAddressCollectionRequestTransfer $servicePointAddressCollectionRequestTransfer
    ): ServicePointAddressCollectionResponseTransfer {
        return $this->getFactory()
            ->createServicePointAddressCreator()
            ->createServicePointAddressCollection($servicePointAddressCollectionRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ServicePointAddressCollectionRequestTransfer $servicePointAddressCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointAddressCollectionResponseTransfer
     */
    public function updateServicePointAddressCollection(
        ServicePointAddressCollectionRequestTransfer $servicePointAddressCollectionRequestTransfer
    ): ServicePointAddressCollectionResponseTransfer {
        return $this->getFactory()
            ->createServicePointAddressUpdater()
            ->updateServicePointAddressCollection($servicePointAddressCollectionRequestTransfer);
    }
}

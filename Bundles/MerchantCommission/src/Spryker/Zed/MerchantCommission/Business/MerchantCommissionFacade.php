<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business;

use Generated\Shared\Transfer\MerchantCommissionCollectionRequestTransfer;
use Generated\Shared\Transfer\MerchantCommissionCollectionResponseTransfer;
use Generated\Shared\Transfer\MerchantCommissionCollectionTransfer;
use Generated\Shared\Transfer\MerchantCommissionCriteriaTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantCommission\Business\MerchantCommissionBusinessFactory getFactory()
 * @method \Spryker\Zed\MerchantCommission\Persistence\MerchantCommissionRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantCommission\Persistence\MerchantCommissionEntityManagerInterface getEntityManager()
 */
class MerchantCommissionFacade extends AbstractFacade implements MerchantCommissionFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantCommissionCriteriaTransfer $merchantCommissionCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionCollectionTransfer
     */
    public function getMerchantCommissionCollection(
        MerchantCommissionCriteriaTransfer $merchantCommissionCriteriaTransfer
    ): MerchantCommissionCollectionTransfer {
        return $this->getFactory()
            ->createMerchantCommissionReader()
            ->getMerchantCommissionCollection($merchantCommissionCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantCommissionCollectionRequestTransfer $merchantCommissionCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionCollectionResponseTransfer
     */
    public function createMerchantCommissionCollection(
        MerchantCommissionCollectionRequestTransfer $merchantCommissionCollectionRequestTransfer
    ): MerchantCommissionCollectionResponseTransfer {
        return $this->getFactory()
            ->createMerchantCommissionCreator()
            ->createMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantCommissionCollectionRequestTransfer $merchantCommissionCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionCollectionResponseTransfer
     */
    public function updateMerchantCommissionCollection(
        MerchantCommissionCollectionRequestTransfer $merchantCommissionCollectionRequestTransfer
    ): MerchantCommissionCollectionResponseTransfer {
        return $this->getFactory()
            ->createMerchantCommissionUpdater()
            ->updateMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantCommissionCollectionRequestTransfer $merchantCommissionCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionCollectionResponseTransfer
     */
    public function importMerchantCommissionCollection(
        MerchantCommissionCollectionRequestTransfer $merchantCommissionCollectionRequestTransfer
    ): MerchantCommissionCollectionResponseTransfer {
        return $this->getFactory()
            ->createMerchantCommissionImporter()
            ->importMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);
    }
}

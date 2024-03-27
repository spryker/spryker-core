<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Business;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCollectionRequestTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantRelationRequest\Business\MerchantRelationRequestBusinessFactory getFactory()
 * @method \Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestEntityManagerInterface getEntityManager()
 */
class MerchantRelationRequestFacade extends AbstractFacade implements MerchantRelationRequestFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer $merchantRelationRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer
     */
    public function getMerchantRelationRequestCollection(
        MerchantRelationRequestCriteriaTransfer $merchantRelationRequestCriteriaTransfer
    ): MerchantRelationRequestCollectionTransfer {
        return $this->getFactory()
            ->createMerchantRelationRequestReader()
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer $merchantRelationRequestCriteriaTransfer
     *
     * @return int
     */
    public function countMerchantRelationRequests(
        MerchantRelationRequestCriteriaTransfer $merchantRelationRequestCriteriaTransfer
    ): int {
        return $this->getRepository()
            ->countMerchantRelationRequests($merchantRelationRequestCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionRequestTransfer $merchantRelationRequestCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer
     */
    public function createMerchantRelationRequestCollection(
        MerchantRelationRequestCollectionRequestTransfer $merchantRelationRequestCollectionRequestTransfer
    ): MerchantRelationRequestCollectionResponseTransfer {
        return $this->getFactory()
            ->createMerchantRelationRequestCreator()
            ->createMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionRequestTransfer $merchantRelationRequestCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer
     */
    public function updateMerchantRelationRequestCollection(
        MerchantRelationRequestCollectionRequestTransfer $merchantRelationRequestCollectionRequestTransfer
    ): MerchantRelationRequestCollectionResponseTransfer {
        return $this->getFactory()
            ->createMerchantRelationRequestUpdater()
            ->updateMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return void
     */
    public function deleteCompanyUserMerchantRelationRequests(CompanyUserTransfer $companyUserTransfer): void
    {
        $this->getFactory()
            ->createMerchantRelationRequestDeleter()
            ->deleteCompanyUserMerchantRelationRequests($companyUserTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return void
     */
    public function deleteCompanyBusinessUnitMerchantRelationRequests(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): void {
        $this->getFactory()
            ->createMerchantRelationRequestDeleter()
            ->deleteCompanyBusinessUnitMerchantRelationRequests($companyBusinessUnitTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer $merchantRelationRequestCollectionResponseTransfer
     *
     * @return void
     */
    public function sendRequestStatusChangeMailNotification(
        MerchantRelationRequestCollectionResponseTransfer $merchantRelationRequestCollectionResponseTransfer
    ): void {
        $this->getFactory()
            ->createRequestStatusChangeMailNotificationSender()
            ->sendRequestStatusChangeMailNotification($merchantRelationRequestCollectionResponseTransfer);
    }
}

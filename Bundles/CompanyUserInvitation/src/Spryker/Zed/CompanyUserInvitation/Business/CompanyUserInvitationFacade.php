<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Business;

use Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationCreateRequestTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationCreateResponseTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationDeleteRequestTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationDeleteResponseTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationGetCollectionRequestTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationImportRequestTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationImportResponseTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationSendBatchResponseTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationSendRequestTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationSendResponseTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationUpdateStatusRequestTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationUpdateStatusResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CompanyUserInvitation\Business\CompanyUserInvitationBusinessFactory getFactory()
 * @method \Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationRepositoryInterface getRepository()
 */
class CompanyUserInvitationFacade extends AbstractFacade implements CompanyUserInvitationFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserInvitationImportRequestTransfer $companyUserInvitationImportRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationImportResponseTransfer
     */
    public function importCompanyUserInvitations(
        CompanyUserInvitationImportRequestTransfer $companyUserInvitationImportRequestTransfer
    ): CompanyUserInvitationImportResponseTransfer {
        return $this->getFactory()
            ->createInvitationImporter()
            ->importCompanyUserInvitations($companyUserInvitationImportRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserInvitationGetCollectionRequestTransfer $companyUserInvitationGetCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer
     */
    public function getCompanyUserInvitationCollection(
        CompanyUserInvitationGetCollectionRequestTransfer $companyUserInvitationGetCollectionRequestTransfer
    ): CompanyUserInvitationCollectionTransfer {
        return $this->getFactory()
            ->createInvitationReader()
            ->getCompanyUserInvitationCollection($companyUserInvitationGetCollectionRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserInvitationSendRequestTransfer $companyUserInvitationSendRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationSendResponseTransfer
     */
    public function sendCompanyUserInvitation(
        CompanyUserInvitationSendRequestTransfer $companyUserInvitationSendRequestTransfer
    ): CompanyUserInvitationSendResponseTransfer {
        return $this->getFactory()
            ->createInvitationSender()
            ->sendCompanyUserInvitation($companyUserInvitationSendRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationSendBatchResponseTransfer
     */
    public function sendCompanyUserInvitations(
        CompanyUserTransfer $companyUserTransfer
    ): CompanyUserInvitationSendBatchResponseTransfer {
        return $this->getFactory()
            ->createInvitationSender()
            ->sendCompanyUserInvitations($companyUserTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserInvitationUpdateStatusRequestTransfer $companyUserInvitationUpdateStatusRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationUpdateStatusResponseTransfer
     */
    public function updateCompanyUserInvitationStatus(
        CompanyUserInvitationUpdateStatusRequestTransfer $companyUserInvitationUpdateStatusRequestTransfer
    ): CompanyUserInvitationUpdateStatusResponseTransfer {
        return $this->getFactory()
            ->createInvitationUpdater()
            ->updateStatus($companyUserInvitationUpdateStatusRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $companyUserInvitationTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationTransfer
     */
    public function getCompanyUserInvitationByHash(
        CompanyUserInvitationTransfer $companyUserInvitationTransfer
    ): CompanyUserInvitationTransfer {
        return $this->getFactory()
            ->createInvitationReader()
            ->getCompanyUserInvitationByHash($companyUserInvitationTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserInvitationCreateRequestTransfer $companyUserInvitationCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationCreateResponseTransfer
     */
    public function createCompanyUserInvitation(
        CompanyUserInvitationCreateRequestTransfer $companyUserInvitationCreateRequestTransfer
    ): CompanyUserInvitationCreateResponseTransfer {
        return $this->getFactory()
            ->createInvitationWriter()
            ->create($companyUserInvitationCreateRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserInvitationDeleteRequestTransfer $companyUserInvitationDeleteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationDeleteResponseTransfer
     */
    public function deleteCompanyUserInvitation(
        CompanyUserInvitationDeleteRequestTransfer $companyUserInvitationDeleteRequestTransfer
    ): CompanyUserInvitationDeleteResponseTransfer {
        return $this->getFactory()
            ->createInvitationDeleter()
            ->delete($companyUserInvitationDeleteRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function install(): void
    {
        $this->getFactory()->createInstaller()->install();
    }
}

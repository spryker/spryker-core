<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyUserInvitation\Zed;

use Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationImportRequestTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationImportResultTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\CompanyUserInvitation\Dependency\Client\CompanyUserInvitationToCustomerClientBridgeInterface;
use Spryker\Client\CompanyUserInvitation\Model\Mapper\InvitationMapperInterface;
use Spryker\Client\CompanyUserInvitation\Model\Reader\InvitationReaderInterface;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;

class CompanyUserInvitationStub implements CompanyUserInvitationStubInterface
{
    /**
     * @var \Spryker\Client\CompanyUserInvitation\Model\Reader\InvitationReaderInterface
     */
    private $invitationReader;

    /**
     * @var \Spryker\Client\CompanyUserInvitation\Model\Mapper\InvitationMapperInterface
     */
    private $invitationMapper;

    /**
     * @var \Spryker\Client\ZedRequest\ZedRequestClient
     */
    protected $zedRequestClient;

    /**
     * @var \Spryker\Client\CompanyUserInvitation\Dependency\Client\CompanyUserInvitationToCustomerClientBridgeInterface
     */
    private $customerClient;

    /**
     * @param \Spryker\Client\CompanyUserInvitation\Model\Reader\InvitationReaderInterface $invitationReader
     * @param \Spryker\Client\CompanyUserInvitation\Model\Mapper\InvitationMapperInterface $invitationMapper
     * @param \Spryker\Client\ZedRequest\ZedRequestClientInterface $zedRequestClient
     * @param \Spryker\Client\CompanyUserInvitation\Dependency\Client\CompanyUserInvitationToCustomerClientBridgeInterface $customerClient
     */
    public function __construct(
        InvitationReaderInterface $invitationReader,
        InvitationMapperInterface $invitationMapper,
        ZedRequestClientInterface $zedRequestClient,
        CompanyUserInvitationToCustomerClientBridgeInterface $customerClient
    ) {
        $this->zedRequestClient = $zedRequestClient;
        $this->invitationReader = $invitationReader;
        $this->invitationMapper = $invitationMapper;
        $this->customerClient = $customerClient;
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyUserInvitationImportResultTransfer
     */
    public function importInvitations(): CompanyUserInvitationImportResultTransfer
    {
        $companyUserInvitationImportRequestTransfer = new CompanyUserInvitationImportRequestTransfer();
        $companyUserInvitationImportRequestTransfer->setInvitationCollection($this->getInvitationCollection());
        $companyUserInvitationImportRequestTransfer->setCustomer($this->getCustomer());

        return $this->zedRequestClient->call(
            '/company-user-invitation/gateway/import-invitations',
            $companyUserInvitationImportRequestTransfer
        );
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer
     */
    protected function getInvitationCollection(): CompanyUserInvitationCollectionTransfer
    {
        $invitations = $this->invitationReader->getInvitations();

        return $this->invitationMapper->mapInvitations($invitations);
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function getCustomer(): CustomerTransfer
    {
        return $this->customerClient->getCustomer();
    }
}

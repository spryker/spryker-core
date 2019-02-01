<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCompanyUserConnector\Business\CompanyUser;

use Generated\Shared\Transfer\CompanyUserIdentifierTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\OauthUserTransfer;
use Spryker\Zed\OauthCompanyUserConnector\Dependency\Facade\OauthCompanyUserConnectorToCompanyUserFacadeInterface;
use Spryker\Zed\OauthCompanyUserConnector\Dependency\Service\OauthCompanyUserConnectorToUtilEncodingServiceInterface;

class CompanyUserProvider implements CompanyUserProviderInterface
{
    protected const COMPANY_STATUS_APPROVED = 'approved';

    /**
     * @var \Spryker\Zed\OauthCompanyUserConnector\Dependency\Facade\OauthCompanyUserConnectorToCompanyUserFacadeInterface
     */
    protected $companyUserFacade;

    /**
     * @var \Spryker\Zed\OauthCompanyUserConnector\Dependency\Service\OauthCompanyUserConnectorToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\OauthCompanyUserConnector\Dependency\Facade\OauthCompanyUserConnectorToCompanyUserFacadeInterface $companyUserFacade
     * @param \Spryker\Zed\OauthCompanyUserConnector\Dependency\Service\OauthCompanyUserConnectorToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        OauthCompanyUserConnectorToCompanyUserFacadeInterface $companyUserFacade,
        OauthCompanyUserConnectorToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->companyUserFacade = $companyUserFacade;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthUserTransfer $oauthUserTransfer
     *
     * @return \Generated\Shared\Transfer\OauthUserTransfer
     */
    public function getOauthCompanyUser(OauthUserTransfer $oauthUserTransfer): OauthUserTransfer
    {
        $oauthUserTransfer->setIsSuccess(false);

        if (!$oauthUserTransfer->getIdCompanyUser() || !$oauthUserTransfer->getCustomerReference()) {
            return $oauthUserTransfer;
        }

        $companyUserTransfer = (new CompanyUserTransfer())
            ->setUuid($oauthUserTransfer->getIdCompanyUser());

        $companyUserTransfer = $this->companyUserFacade->findActiveCompanyUserByUuid($companyUserTransfer);

        if ($companyUserTransfer !== null
            && $companyUserTransfer->getCustomer()->getCustomerReference() !== $oauthUserTransfer->getCustomerReference()) {
            return $oauthUserTransfer;
        }

        $companyUserIdentifierTransfer = (new CompanyUserIdentifierTransfer())
            ->setCustomerReference($companyUserTransfer->getCustomer()->getCustomerReference())
            ->setIdCustomer($companyUserTransfer->getCustomer()->getIdCustomer())
            ->setIdCompanyUser($companyUserTransfer->getUuid());

        $oauthUserTransfer
            ->setUserIdentifier($this->utilEncodingService->encodeJson($companyUserIdentifierTransfer->toArray()))
            ->setIsSuccess(true);

        return $oauthUserTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param string $customerReference
     *
     * @return bool
     */
    protected function isCompanyUserAuthorized(CompanyUserTransfer $companyUserTransfer, string $customerReference): bool
    {
        return $companyUserTransfer !== null
            && $companyUserTransfer->getCustomer()->getCustomerReference() === $customerReference;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return bool
     */
    protected function isCompanyUserActive(CompanyUserTransfer $companyUserTransfer): bool
    {
        return $companyUserTransfer->getIsActive()
            && $companyUserTransfer->getCompany()->getIsActive()
            && $companyUserTransfer->getCompany()->getStatus() === static::COMPANY_STATUS_APPROVED;
    }
}

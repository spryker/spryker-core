<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCompanyUser\Business\CompanyUser;

use Generated\Shared\Transfer\CompanyUserIdentifierTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\OauthUserTransfer;
use Spryker\Zed\OauthCompanyUser\Dependency\Facade\OauthCompanyUserToCompanyUserFacadeInterface;
use Spryker\Zed\OauthCompanyUser\Dependency\Service\OauthCompanyUserToUtilEncodingServiceInterface;

class CompanyUserProvider implements CompanyUserProviderInterface
{
    /**
     * @var \Spryker\Zed\OauthCompanyUser\Dependency\Facade\OauthCompanyUserToCompanyUserFacadeInterface
     */
    protected $companyUserFacade;

    /**
     * @var \Spryker\Zed\OauthCompanyUser\Dependency\Service\OauthCompanyUserToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\OauthCompanyUser\Dependency\Facade\OauthCompanyUserToCompanyUserFacadeInterface $companyUserFacade
     * @param \Spryker\Zed\OauthCompanyUser\Dependency\Service\OauthCompanyUserToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        OauthCompanyUserToCompanyUserFacadeInterface $companyUserFacade,
        OauthCompanyUserToUtilEncodingServiceInterface $utilEncodingService
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

        if ($companyUserTransfer === null
            || $companyUserTransfer->getCustomer()->getCustomerReference() !== $oauthUserTransfer->getCustomerReference()) {
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
}

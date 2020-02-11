<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCompanyUser\Business\Reader;

use Generated\Shared\Transfer\CompanyUserAccessTokenRequestTransfer;
use Generated\Shared\Transfer\CompanyUserIdentifierTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OauthAccessTokenValidationRequestTransfer;
use Spryker\Zed\OauthCompanyUser\Dependency\Facade\OauthCompanyUserToCustomerFacadeInterface;
use Spryker\Zed\OauthCompanyUser\Dependency\Facade\OauthCompanyUserToOauthFacadeInterface;
use Spryker\Zed\OauthCompanyUser\Dependency\Service\OauthCompanyUserToUtilEncodingServiceInterface;

class CompanyUserAccessTokenReader implements CompanyUserAccessTokenReaderInterface
{
    protected const TOKEN_TYPE = 'Bearer';

    /**
     * @var \Spryker\Zed\OauthCompanyUser\Dependency\Facade\OauthCompanyUserToOauthFacadeInterface
     */
    protected $oauthFacade;

    /**
     * @var \Spryker\Zed\OauthCompanyUser\Dependency\Service\OauthCompanyUserToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\OauthCompanyUser\Dependency\Facade\OauthCompanyUserToCustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @var \Spryker\Zed\OauthCompanyUserExtension\Dependency\Plugin\CustomerExpanderPluginInterface[]
     */
    protected $customerExpanderPlugins;

    /**
     * @param \Spryker\Zed\OauthCompanyUser\Dependency\Facade\OauthCompanyUserToOauthFacadeInterface $oauthFacade
     * @param \Spryker\Zed\OauthCompanyUser\Dependency\Service\OauthCompanyUserToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\OauthCompanyUser\Dependency\Facade\OauthCompanyUserToCustomerFacadeInterface $customerFacade
     * @param \Spryker\Zed\OauthCompanyUserExtension\Dependency\Plugin\CustomerExpanderPluginInterface[] $customerExpanderPlugins
     */
    public function __construct(
        OauthCompanyUserToOauthFacadeInterface $oauthFacade,
        OauthCompanyUserToUtilEncodingServiceInterface $utilEncodingService,
        OauthCompanyUserToCustomerFacadeInterface $customerFacade,
        array $customerExpanderPlugins
    ) {
        $this->oauthFacade = $oauthFacade;
        $this->utilEncodingService = $utilEncodingService;
        $this->customerFacade = $customerFacade;
        $this->customerExpanderPlugins = $customerExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserAccessTokenRequestTransfer $companyUserAccessTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function getCustomerByAccessToken(CompanyUserAccessTokenRequestTransfer $companyUserAccessTokenRequestTransfer): CustomerResponseTransfer
    {
        $companyUserAccessTokenRequestTransfer->requireAccessToken();

        $oauthAccessTokenValidationResponseTransfer = $this->oauthFacade->validateAccessToken(
            (new OauthAccessTokenValidationRequestTransfer())
                ->setAccessToken($companyUserAccessTokenRequestTransfer->getAccessToken())
                ->setType(static::TOKEN_TYPE)
        );

        if (!$oauthAccessTokenValidationResponseTransfer->getIsValid()) {
            return (new CustomerResponseTransfer())
                ->setIsSuccess(false)
                ->setHasCustomer(false);
        }

        $decodedPayload = $this->utilEncodingService->decodeJson($oauthAccessTokenValidationResponseTransfer->getOauthUserId(), true);
        $companyUserIdentifierTransfer = (new CompanyUserIdentifierTransfer())->fromArray($decodedPayload, true);

        $customerTransfer = $this->getCustomerByCompanyUserIdentifier($companyUserIdentifierTransfer);
        $customerTransfer = $this->executeCustomerExpanderPlugins($customerTransfer, $companyUserIdentifierTransfer);

        return (new CustomerResponseTransfer())
            ->setCustomerTransfer($customerTransfer)
            ->setIsSuccess(true)
            ->setHasCustomer(true);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserIdentifierTransfer $companyUserIdentifierTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function getCustomerByCompanyUserIdentifier(CompanyUserIdentifierTransfer $companyUserIdentifierTransfer): CustomerTransfer
    {
        $companyUserIdentifierTransfer
            ->requireIdCustomer()
            ->requireIdCompanyUser();

        $customerTransfer = (new CustomerTransfer())
            ->setIdCustomer($companyUserIdentifierTransfer->getIdCustomer())
            ->setCompanyUserTransfer((new CompanyUserTransfer())->setIdCompanyUser((int)$companyUserIdentifierTransfer->getIdCompanyUser()));

        return $this->customerFacade->getCustomer($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\CompanyUserIdentifierTransfer $companyUserIdentifierTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function executeCustomerExpanderPlugins(
        CustomerTransfer $customerTransfer,
        CompanyUserIdentifierTransfer $companyUserIdentifierTransfer
    ): CustomerTransfer {
        foreach ($this->customerExpanderPlugins as $customerExpanderPlugin) {
            $customerTransfer = $customerExpanderPlugin->expand($customerTransfer, $companyUserIdentifierTransfer);
        }

        return $customerTransfer;
    }
}

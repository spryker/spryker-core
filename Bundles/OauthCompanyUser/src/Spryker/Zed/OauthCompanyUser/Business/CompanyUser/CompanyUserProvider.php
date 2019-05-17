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
     * @var \Spryker\Zed\OauthCompanyUserExtension\Dependency\Plugin\OauthCompanyUserIdentifierExpanderPluginInterface[]
     */
    protected $oauthCompanyUserIdentifierExpanderPlugins;

    /**
     * @param \Spryker\Zed\OauthCompanyUser\Dependency\Facade\OauthCompanyUserToCompanyUserFacadeInterface $companyUserFacade
     * @param \Spryker\Zed\OauthCompanyUser\Dependency\Service\OauthCompanyUserToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\OauthCompanyUserExtension\Dependency\Plugin\OauthCompanyUserIdentifierExpanderPluginInterface[] $oauthCompanyUserIdentifierExpanderPlugins
     */
    public function __construct(
        OauthCompanyUserToCompanyUserFacadeInterface $companyUserFacade,
        OauthCompanyUserToUtilEncodingServiceInterface $utilEncodingService,
        array $oauthCompanyUserIdentifierExpanderPlugins
    ) {
        $this->companyUserFacade = $companyUserFacade;
        $this->utilEncodingService = $utilEncodingService;
        $this->oauthCompanyUserIdentifierExpanderPlugins = $oauthCompanyUserIdentifierExpanderPlugins;
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

        $companyUserTransfer = $this->findActiveCompanyUser($oauthUserTransfer);

        if ($companyUserTransfer === null
            || $companyUserTransfer->getCustomer()->getCustomerReference() !== $oauthUserTransfer->getCustomerReference()) {
            return $oauthUserTransfer;
        }

        return $this->prepareOauthUserTransfer($oauthUserTransfer, $companyUserTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OauthUserTransfer $oauthUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    protected function findActiveCompanyUser(OauthUserTransfer $oauthUserTransfer)
    {
        $companyUserTransfer = (new CompanyUserTransfer())
            ->setUuid($oauthUserTransfer->getIdCompanyUser());

        $companyUserTransfer = $this->companyUserFacade->findActiveCompanyUserByUuid($companyUserTransfer);

        return $companyUserTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthUserTransfer $oauthUserTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\OauthUserTransfer
     */
    protected function prepareOauthUserTransfer(OauthUserTransfer $oauthUserTransfer, CompanyUserTransfer $companyUserTransfer): OauthUserTransfer
    {
        $companyUserIdentifierTransfer = (new CompanyUserIdentifierTransfer())
            ->setCustomerReference($companyUserTransfer->getCustomer()->getCustomerReference())
            ->setIdCustomer($companyUserTransfer->getCustomer()->getIdCustomer())
            ->setIdCompanyUser($companyUserTransfer->getUuid());

        $companyUserIdentifierTransfer = $this->executeExpanderPlugins($companyUserIdentifierTransfer, $companyUserTransfer);

        $oauthUserTransfer
            ->setUserIdentifier($this->utilEncodingService->encodeJson($companyUserIdentifierTransfer->toArray()))
            ->setIsSuccess(true);

        return $oauthUserTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserIdentifierTransfer $companyUserIdentifierTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserIdentifierTransfer
     */
    protected function executeExpanderPlugins(
        CompanyUserIdentifierTransfer $companyUserIdentifierTransfer,
        CompanyUserTransfer $companyUserTransfer
    ): CompanyUserIdentifierTransfer {
        foreach ($this->oauthCompanyUserIdentifierExpanderPlugins as $oauthCompanyUserIdentifierExpanderPlugin) {
            $companyUserIdentifierTransfer = $oauthCompanyUserIdentifierExpanderPlugin->expandCompanyUserIdentifier(
                $companyUserIdentifierTransfer,
                $companyUserTransfer
            );
        }

        return $companyUserIdentifierTransfer;
    }
}

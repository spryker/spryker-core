<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCustomerConnector\Business\Model;

use Generated\Shared\Transfer\CustomerIdentifierTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OauthUserTransfer;
use Spryker\Zed\OauthCustomerConnector\Dependency\Facade\OauthCustomerConnectorToCustomerFacadeInterface;
use Spryker\Zed\OauthCustomerConnector\Dependency\Service\OauthCustomerConnectorToUtilEncodingServiceInterface;

class CustomerProvider implements CustomerProviderInterface
{
    /**
     * @var \Spryker\Zed\OauthCustomerConnector\Dependency\Facade\OauthCustomerConnectorToCustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @var \Spryker\Zed\OauthCustomerConnector\Dependency\Service\OauthCustomerConnectorToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\OauthCustomerConnectorExtension\Dependency\Plugin\OauthCustomerIdentifierExpanderPluginInterface[]
     */
    protected $oauthCustomerIdentifierExpanderPlugins;

    /**
     * @param \Spryker\Zed\OauthCustomerConnector\Dependency\Facade\OauthCustomerConnectorToCustomerFacadeInterface $customerFacade
     * @param \Spryker\Zed\OauthCustomerConnector\Dependency\Service\OauthCustomerConnectorToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\OauthCustomerConnectorExtension\Dependency\Plugin\OauthCustomerIdentifierExpanderPluginInterface[] $oauthCustomerIdentifierExpanderPlugins
     */
    public function __construct(
        OauthCustomerConnectorToCustomerFacadeInterface $customerFacade,
        OauthCustomerConnectorToUtilEncodingServiceInterface $utilEncodingService,
        array $oauthCustomerIdentifierExpanderPlugins
    ) {
        $this->customerFacade = $customerFacade;
        $this->utilEncodingService = $utilEncodingService;
        $this->oauthCustomerIdentifierExpanderPlugins = $oauthCustomerIdentifierExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthUserTransfer $oauthUserTransfer
     *
     * @return \Generated\Shared\Transfer\OauthUserTransfer
     */
    public function getCustomerOauthUser(OauthUserTransfer $oauthUserTransfer): OauthUserTransfer
    {
        $customerTransfer = (new CustomerTransfer())
            ->setEmail($oauthUserTransfer->getUsername())
            ->setPassword($oauthUserTransfer->getPassword());

        $oauthUserTransfer->setIsSuccess(false);

        $isAuthorized = $this->customerFacade->tryAuthorizeCustomerByEmailAndPassword($customerTransfer);

        if (!$isAuthorized) {
            return $oauthUserTransfer;
        }

        $customerTransfer = $this->customerFacade->getCustomer($customerTransfer);

        $customerIdentifierTransfer = $this->executeOauthCustomerIdentifierExpanderPlugins($customerTransfer);

        return $oauthUserTransfer
            ->setUserIdentifier($this->utilEncodingService->encodeJson($customerIdentifierTransfer->toArray()))
            ->setIsSuccess(true);
    }

    /**
     * @param \Generated\Shared\Transfer\OauthUserTransfer $oauthUserTransfer
     *
     * @return \Generated\Shared\Transfer\OauthUserTransfer
     */
    public function getCustomerImpersonationOauthUser(OauthUserTransfer $oauthUserTransfer): OauthUserTransfer
    {
        $oauthUserTransfer->setIsSuccess(false);

        if (!$oauthUserTransfer->getCustomerReference()) {
            return $oauthUserTransfer;
        }

        $customerResponseTransfer = $this->customerFacade->findCustomerByReference($oauthUserTransfer->getCustomerReference());

        if (!$customerResponseTransfer->getIsSuccess()) {
            return $oauthUserTransfer;
        }

        $customerIdentifierTransfer = $this->executeOauthCustomerIdentifierExpanderPlugins(
            $customerResponseTransfer->getCustomerTransfer()
        );

        return $oauthUserTransfer
            ->setUserIdentifier($this->utilEncodingService->encodeJson($customerIdentifierTransfer->toArray()))
            ->setIsSuccess(true);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerIdentifierTransfer
     */
    protected function executeOauthCustomerIdentifierExpanderPlugins(CustomerTransfer $customerTransfer): CustomerIdentifierTransfer
    {
        $customerIdentifierTransfer = (new CustomerIdentifierTransfer())
            ->setCustomerReference($customerTransfer->getCustomerReference())
            ->setIdCustomer($customerTransfer->getIdCustomer());

        foreach ($this->oauthCustomerIdentifierExpanderPlugins as $oauthCustomerIdentifierExpanderPlugin) {
            $customerIdentifierTransfer = $oauthCustomerIdentifierExpanderPlugin
                ->expandCustomerIdentifier($customerIdentifierTransfer, $customerTransfer);
        }

        return $customerIdentifierTransfer;
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCustomerConnector\Business\Model;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OauthUserTransfer;
use Spryker\Zed\OauthCustomerConnector\Dependency\Facade\OauthCustomerConnectorToCustomerFacadeInterface;

class CustomerProvider implements CustomerProviderInterface
{
    /**
     * @var \Spryker\Zed\OauthCustomerConnector\Dependency\Facade\OauthCustomerConnectorToCustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @param \Spryker\Zed\OauthCustomerConnector\Dependency\Facade\OauthCustomerConnectorToCustomerFacadeInterface $customerFacade
     */
    public function __construct(OauthCustomerConnectorToCustomerFacadeInterface $customerFacade)
    {
        $this->customerFacade = $customerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthUserTransfer $oauthUserTransfer
     *
     * @return \Generated\Shared\Transfer\OauthUserTransfer
     */
    public function getCustomer(OauthUserTransfer $oauthUserTransfer): OauthUserTransfer
    {
        $customerTransfer = (new CustomerTransfer())
            ->setEmail($oauthUserTransfer->getUsername())
            ->setPassword($oauthUserTransfer->getPassword());

        $oauthUserTransfer->setIsSuccess(false);

        $isAuthorized = $this->customerFacade->tryAuthorizeCustomerByEmailAndPassword($customerTransfer);

        if ($isAuthorized) {
            $customerTransfer = $this->customerFacade->getCustomer($customerTransfer);
            $customerIdentifier = json_encode(
                [
                    'customer_reference' => $customerTransfer->getCustomerReference(),
                    'id_customer' => $customerTransfer->getIdCustomer(),
                ]
            );
            $oauthUserTransfer
                ->setUserIdentifier($customerIdentifier)
                ->setIsSuccess(true);
        }

        return $oauthUserTransfer;
    }
}

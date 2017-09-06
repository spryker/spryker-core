<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerUserConnectorGui\Business\Model;

use Generated\Shared\Transfer\CustomerUserConnectionTransfer;
use Spryker\Zed\CustomerUserConnectorGui\Dependency\QueryContainer\CustomerUserConnectorGuiToCustomerQueryContainerInterface;

class CustomerUserConnectionUpdater implements CustomerUserConnectionUpdaterInterface
{

    /**
     * @var \Spryker\Zed\CustomerUserConnectorGui\Dependency\QueryContainer\CustomerUserConnectorGuiToCustomerQueryContainerInterface
     */
    protected $customerUserConnectorGuiToCustomerQueryContainerBridge;

    /**
     * @param \Spryker\Zed\CustomerUserConnectorGui\Dependency\QueryContainer\CustomerUserConnectorGuiToCustomerQueryContainerInterface $customerUserConnectorGuiToCustomerQueryContainerBridge
     */
    public function __construct(CustomerUserConnectorGuiToCustomerQueryContainerInterface $customerUserConnectorGuiToCustomerQueryContainerBridge)
    {
        $this->customerUserConnectorGuiToCustomerQueryContainerBridge = $customerUserConnectorGuiToCustomerQueryContainerBridge;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerUserConnectionTransfer $customerUserConnectionTransfer
     *
     * @return bool
     */
    public function updateCustomerUserConnection(CustomerUserConnectionTransfer $customerUserConnectionTransfer)
    {
        foreach ($customerUserConnectionTransfer->getIdCustomersToAssign() as $customerIdToAssign) {
            $customerEntity = $this->customerUserConnectorGuiToCustomerQueryContainerBridge->queryCustomerById($customerIdToAssign)->findOne();
            $customerEntity->setFkUser($customerUserConnectionTransfer->getIdUser());
            $customerEntity->save();
        }

        foreach ($customerUserConnectionTransfer->getIdCustomersToDeAssign() as $customerIdToDeAssign) {
            $customerEntity = $this->customerUserConnectorGuiToCustomerQueryContainerBridge->queryCustomerById($customerIdToDeAssign)->findOne();
            $customerEntity->setFkUser(null);
            $customerEntity->save();
        }

        return true;
    }

}

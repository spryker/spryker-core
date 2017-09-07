<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerUserConnectorGui\Business\Model;

use Generated\Shared\Transfer\CustomerUserConnectionUpdateTransfer;
use Spryker\Zed\CustomerUserConnectorGui\Dependency\QueryContainer\CustomerUserConnectorGuiToCustomerQueryContainerInterface;

class CustomerUserConnectionUpdater implements CustomerUserConnectionUpdaterInterface
{

    /**
     * @var \Spryker\Zed\CustomerUserConnectorGui\Dependency\QueryContainer\CustomerUserConnectorGuiToCustomerQueryContainerInterface
     */
    protected $customerQueryContainer;

    /**
     * @param \Spryker\Zed\CustomerUserConnectorGui\Dependency\QueryContainer\CustomerUserConnectorGuiToCustomerQueryContainerInterface $customerQueryContainer
     */
    public function __construct(CustomerUserConnectorGuiToCustomerQueryContainerInterface $customerQueryContainer)
    {
        $this->customerQueryContainer = $customerQueryContainer;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerUserConnectionUpdateTransfer $customerUserConnectionUpdateTransfer
     *
     * @return bool
     */
    public function updateCustomerUserConnection(CustomerUserConnectionUpdateTransfer $customerUserConnectionUpdateTransfer)
    {
        $this->assignUsersToCustomers($customerUserConnectionUpdateTransfer);
        $this->deAssignUsersFromCustomers($customerUserConnectionUpdateTransfer);

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerUserConnectionUpdateTransfer $customerUserConnectionUpdateTransfer
     *
     * @return void
     */
    protected function assignUsersToCustomers(CustomerUserConnectionUpdateTransfer $customerUserConnectionUpdateTransfer)
    {
        foreach ($customerUserConnectionUpdateTransfer->getIdCustomersToAssign() as $customerIdToAssign) {
            $customerEntity = $this->customerQueryContainer->queryCustomerById($customerIdToAssign)->findOne();
            $customerEntity->setFkUser($customerUserConnectionUpdateTransfer->getIdUser());
            $customerEntity->save();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerUserConnectionUpdateTransfer $customerUserConnectionUpdateTransfer
     *
     * @return void
     */
    protected function deAssignUsersFromCustomers(CustomerUserConnectionUpdateTransfer $customerUserConnectionUpdateTransfer)
    {
        foreach ($customerUserConnectionUpdateTransfer->getIdCustomersToDeAssign() as $customerIdToDeAssign) {
            $customerEntity = $this->customerQueryContainer->queryCustomerById($customerIdToDeAssign)->findOne();
            $customerEntity->setFkUser(null);
            $customerEntity->save();
        }
    }

}

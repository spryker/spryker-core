<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccess\Business\CustomerAccess;

use Generated\Shared\Transfer\CustomerAccessTransfer;
use Spryker\Zed\CustomerAccess\Persistence\CustomerAccessEntityManagerInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class CustomerAccessUpdater implements CustomerAccessUpdaterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\CustomerAccess\Persistence\CustomerAccessEntityManagerInterface
     */
    protected $customerAccessEntityManager;

    /**
     * @param \Spryker\Zed\CustomerAccess\Persistence\CustomerAccessEntityManagerInterface $customerAccessEntityManager
     */
    public function __construct(CustomerAccessEntityManagerInterface $customerAccessEntityManager)
    {
        $this->customerAccessEntityManager = $customerAccessEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerAccessTransfer $customerAccessTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function updateUnauthenticatedCustomerAccess(CustomerAccessTransfer $customerAccessTransfer): CustomerAccessTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($customerAccessTransfer) {
            $this->customerAccessEntityManager->setAllContentTypesToAccessible();

            return $this->customerAccessEntityManager->setContentTypesToInaccessible($customerAccessTransfer);
        });
    }
}

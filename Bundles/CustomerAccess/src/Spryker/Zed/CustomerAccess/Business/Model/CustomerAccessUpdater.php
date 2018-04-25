<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccess\Business\Model;

use Generated\Shared\Transfer\CustomerAccessTransfer;
use Spryker\Zed\CustomerAccess\Persistence\CustomerAccessEntityManagerInterface;

class CustomerAccessUpdater implements CustomerAccessUpdaterInterface
{
    /**
     * @var \Spryker\Zed\CustomerAccess\Persistence\CustomerAccessEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \Spryker\Zed\CustomerAccess\Persistence\CustomerAccessEntityManagerInterface $entityManager
     */
    public function __construct(CustomerAccessEntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerAccessTransfer $customerAccessTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function updateUnauthenticatedCustomerAccess(CustomerAccessTransfer $customerAccessTransfer): CustomerAccessTransfer
    {
        return $this->entityManager->updateUnauthenticatedCustomerAccess($customerAccessTransfer);
    }
}

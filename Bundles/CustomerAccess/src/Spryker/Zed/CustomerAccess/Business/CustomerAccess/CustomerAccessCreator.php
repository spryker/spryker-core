<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccess\Business\CustomerAccess;

use Generated\Shared\Transfer\CustomerAccessTransfer;
use Spryker\Zed\CustomerAccess\Persistence\CustomerAccessEntityManagerInterface;

class CustomerAccessCreator implements CustomerAccessCreatorInterface
{
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
     * @param string $contentType
     * @param bool $isRestricted
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function createCustomerAccess(string $contentType, bool $isRestricted): CustomerAccessTransfer
    {
        return $this->customerAccessEntityManager->createCustomerAccess($contentType, $isRestricted);
    }
}

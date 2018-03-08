<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccessGui\Dependency\Facade;


class CustomerAccessGuiToCustomerAccessFacadeBridge implements CustomerAccessGuiToCustomerAccessFacadeInterface
{
    /**
     * @var \Spryker\Zed\CustomerAccess\Business\CustomerAccessFacadeInterface
     */
    protected $customerAccessFacade;

    /**
     * @param \Spryker\Zed\CustomerAccess\Business\CustomerAccessFacadeInterface $customerAccessFacade
     */
    public function __construct($customerAccessFacade)
    {
        $this->customerAccessFacade = $customerAccessFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function findUnauthenticatedCustomerAccess()
    {
        return $this->customerAccessFacade->findUnauthenticatedCustomerAccess();
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccessGui\Dependency\Facade;

use Generated\Shared\Transfer\CustomerAccessTransfer;

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
     * @param \Generated\Shared\Transfer\CustomerAccessTransfer $customerAccessTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function updateUnauthenticatedCustomerAccess(CustomerAccessTransfer $customerAccessTransfer): CustomerAccessTransfer
    {
        return $this->customerAccessFacade->updateUnauthenticatedCustomerAccess($customerAccessTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function getAllContentTypes(): CustomerAccessTransfer
    {
        return $this->customerAccessFacade->getAllContentTypes();
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function getUnrestrictedContentTypes(): CustomerAccessTransfer
    {
        return $this->customerAccessFacade->getUnrestrictedContentTypes();
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function getRestrictedContentTypes(): CustomerAccessTransfer
    {
        return $this->customerAccessFacade->getRestrictedContentTypes();
    }
}

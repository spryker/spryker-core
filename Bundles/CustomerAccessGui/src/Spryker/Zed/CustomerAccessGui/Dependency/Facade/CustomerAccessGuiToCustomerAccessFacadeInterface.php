<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccessGui\Dependency\Facade;

use Generated\Shared\Transfer\CustomerAccessTransfer;

interface CustomerAccessGuiToCustomerAccessFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerAccessTransfer $customerAccessTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function updateUnauthenticatedCustomerAccess(CustomerAccessTransfer $customerAccessTransfer): CustomerAccessTransfer;

    /**
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function getAllContentTypes(): CustomerAccessTransfer;

    /**
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function getContentTypesWithUnauthenticatedCustomerAccess(): CustomerAccessTransfer;
}

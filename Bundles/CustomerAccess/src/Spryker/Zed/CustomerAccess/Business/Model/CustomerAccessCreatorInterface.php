<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccess\Business\Model;

use Generated\Shared\Transfer\CustomerAccessTransfer;

interface CustomerAccessCreatorInterface
{
    /**
     * @param string $contentType
     * @param bool $isRestricted
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function createCustomerAccess(string $contentType, bool $isRestricted): CustomerAccessTransfer;
}

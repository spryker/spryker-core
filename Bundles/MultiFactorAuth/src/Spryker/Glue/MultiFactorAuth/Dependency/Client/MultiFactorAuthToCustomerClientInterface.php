<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MultiFactorAuth\Dependency\Client;

use Generated\Shared\Transfer\CustomerTransfer;

interface MultiFactorAuthToCustomerClientInterface
{
    /**
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerById(int $idCustomer): CustomerTransfer;
}

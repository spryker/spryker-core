<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AuthRestApi\Dependency\Client;

use Generated\Shared\Transfer\CustomerTransfer;

interface AuthRestApiToCustomerClientInterface
{
    /**
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerById($idCustomer): CustomerTransfer;
}

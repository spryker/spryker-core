<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Glue\CartsRestApi\Helper;

use Codeception\Module\REST;
use Generated\Shared\Transfer\CustomerTransfer;

class CartsRestApiHelper extends REST
{
    /**
     * Publishes access token
     *
     * @part json
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function amUnauthorizedGlueCustomer(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        $this->haveHttpHeader('X-Anonymous-Customer-Unique-Id', $customerTransfer->getCustomerReference());

        return $customerTransfer;
    }
}

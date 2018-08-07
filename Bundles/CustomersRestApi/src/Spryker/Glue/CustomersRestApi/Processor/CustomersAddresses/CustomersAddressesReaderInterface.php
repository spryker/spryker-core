<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\CustomersAddresses;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

interface CustomersAddressesReaderInterface
{
    /**
     * @param string $customerReference
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function readByIdentifier(string $customerReference): ?RestResourceInterface;
}

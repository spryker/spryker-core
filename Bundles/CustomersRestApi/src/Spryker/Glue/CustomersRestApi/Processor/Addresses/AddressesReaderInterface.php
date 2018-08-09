<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Addresses;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface AddressesReaderInterface
{
    /**
     * @param string $customerReference
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function readByCustomerReference(string $customerReference): RestResponseInterface;

    /**
     * @param string $uuid
     * @param string $customerReference
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function readByUuid(string $uuid, string $customerReference): RestResponseInterface;
}

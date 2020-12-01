<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\RestAddressAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface AddressRestResponseBuilderInterface
{
    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createRestResponse(): RestResponseInterface;

    /**
     * @param string $addressUuid
     * @param string $customerReference
     * @param \Generated\Shared\Transfer\RestAddressAttributesTransfer $restAddressAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createAddressRestResource(
        string $addressUuid,
        string $customerReference,
        RestAddressAttributesTransfer $restAddressAttributesTransfer
    ): RestResourceInterface;
}

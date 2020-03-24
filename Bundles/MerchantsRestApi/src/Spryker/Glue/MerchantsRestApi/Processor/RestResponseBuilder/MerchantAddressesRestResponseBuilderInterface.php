<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface MerchantAddressesRestResponseBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantStorageProfileAddressTransfer[] $merchantAddressesStorageTransfers
     * @param string $merchantReference
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createMerchantAddressesRestResource(array $merchantAddressesStorageTransfers, string $merchantReference): RestResourceInterface;

    /**
     * @param \Generated\Shared\Transfer\MerchantStorageProfileAddressTransfer[] $merchantAddressesStorageTransfers
     * @param string $merchantReference
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createMerchantAddressesRestResponse(array $merchantAddressesStorageTransfers, string $merchantReference): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createMerchantNotFoundError(): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createMerchantIdentifierMissingErrorResponse(): RestResponseInterface;
}

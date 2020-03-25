<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantOpeningHoursRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface MerchantOpeningHoursRestResponseBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer $merchantOpeningHoursStorageTransfer
     * @param string $merchantReference
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createMerchantOpeningHoursRestResource(
        MerchantOpeningHoursStorageTransfer $merchantOpeningHoursStorageTransfer,
        string $merchantReference
    ): RestResourceInterface;

    /**
     * @param \Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer $merchantOpeningHoursStorageTransfer
     * @param string $merchantReference
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createMerchantOpeningHoursRestResponse(
        MerchantOpeningHoursStorageTransfer $merchantOpeningHoursStorageTransfer,
        string $merchantReference
    ): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createMerchantNotFoundError(): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createMerchantIdentifierMissingErrorResponse(): RestResponseInterface;
}

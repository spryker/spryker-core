<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestAgentsRestApi\Processor\RestResponseBuilder;

use ArrayObject;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface QuoteRequestRestResponseBuilderInterface
{
    /**
     * @phpstan-param ArrayObject<int, \Generated\Shared\Transfer\MessageTransfer> $messageTransfers
     *
     * @param \Generated\Shared\Transfer\MessageTransfer[]|\ArrayObject $messageTransfers
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createFailedErrorResponse(ArrayObject $messageTransfers): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCompanyUserNotFoundErrorResponse(): RestResponseInterface;
}

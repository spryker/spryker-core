<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SharedCartsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\ShareDetailTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface SharedCartRestResponseBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShareDetailTransfer|null $shareDetailTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createSharedCartRestResponse(?ShareDetailTransfer $shareDetailTransfer = null): RestResponseInterface;

    /**
     * @param int $status
     * @param string $code
     * @param string $detail
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createRestErrorResponse(int $status, string $code, string $detail): RestResponseInterface;

    /**
     * @param string $errorIdentifier
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createErrorResponseFromErrorIdentifier(string $errorIdentifier): RestResponseInterface;
}

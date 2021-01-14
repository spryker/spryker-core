<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SecurityBlockerRestApi\Processor\Checker;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface AuthenticationCheckerInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param string $expectedResourceType
     *
     * @return bool
     */
    public function isAuthenticationRequest(RestRequestInterface $restRequest, string $expectedResourceType): bool;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     * @param string $expectedCode
     *
     * @return bool
     */
    public function isFailedAuthenticationResponse(RestResponseInterface $restResponse, string $expectedCode): bool;
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SecurityBlockerRestApi\Processor\Checker;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationChecker implements AuthenticationCheckerInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param string $expectedResourceType
     *
     * @return bool
     */
    public function isAuthenticationRequest(RestRequestInterface $restRequest, string $expectedResourceType): bool
    {
        return $restRequest->getResource()->getType() === $expectedResourceType
            && $restRequest->getHttpRequest()->getMethod() === Request::METHOD_POST;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     * @param string $expectedCode
     *
     * @return bool
     */
    public function isFailedAuthenticationResponse(RestResponseInterface $restResponse, string $expectedCode): bool
    {
        if ($restResponse->getStatus() === Response::HTTP_UNAUTHORIZED) {
            return false;
        }

        foreach ($restResponse->getErrors() as $restErrorMessageTransfer) {
            if ($restErrorMessageTransfer->getCode() === $expectedCode) {
                return true;
            }
        }

        return false;
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Request;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsHttpRequestValidator implements CorsHttpRequestValidatorInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer|null
     */
    public function validate(Request $request): ?RestErrorMessageTransfer
    {
        if ($request->getMethod() !== Request::METHOD_OPTIONS) {
            return null;
        }

        $headerData = $request->headers->all();
        if (
            !isset($headerData[RequestConstantsInterface::HEADER_ACCESS_CONTROL_REQUEST_METHOD]) ||
            !isset($headerData[RequestConstantsInterface::HEADER_ACCESS_CONTROL_REQUEST_HEADERS]) ||
            !isset($headerData[RequestConstantsInterface::HEADER_ORIGIN])
        ) {
            return (new RestErrorMessageTransfer())
                ->setDetail('One or more of the required headers (access-control-request-method, access-control-request-headers, origin) for the options method are missing.')
                ->setStatus(Response::HTTP_NOT_IMPLEMENTED);
        }

        return null;
    }
}

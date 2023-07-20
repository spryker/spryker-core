<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Response;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * @deprecated Will be removed without replacement.
 */
interface ResponseHeadersInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Response $httpResponse
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addHeaders(
        Response $httpResponse,
        RestResponseInterface $restResponse,
        RestRequestInterface $restRequest
    ): Response;

    /**
     * @param \Symfony\Component\HttpFoundation\Response $httpResponse
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addCorsAllowOriginHeader(Response $httpResponse): Response;
}

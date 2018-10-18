<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\ContentType;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Response;

interface ContentTypeResolverInterface
{
    /**
     * @param string $contentType
     *
     * @return array
     */
    public function matchContentType(string $contentType): array;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Symfony\Component\HttpFoundation\Response $httpResponse
     *
     * @return void
     */
    public function addResponseHeaders(RestRequestInterface $restRequest, Response $httpResponse): void;
}

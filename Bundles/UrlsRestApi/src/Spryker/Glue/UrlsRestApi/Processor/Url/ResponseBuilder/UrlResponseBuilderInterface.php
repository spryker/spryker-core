<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UrlsRestApi\Processor\Url\ResponseBuilder;

use Generated\Shared\Transfer\RestUrlsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface UrlResponseBuilderInterface
{
    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createUrlRequestParamMissingErrorResponse(): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createUrlNotFoundErrorResponse(): RestResponseInterface;

    /**
     * @param \Generated\Shared\Transfer\RestUrlsAttributesTransfer $restUrlsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createUrlsResourceResponse(
        RestUrlsAttributesTransfer $restUrlsAttributesTransfer
    ): RestResponseInterface;
}

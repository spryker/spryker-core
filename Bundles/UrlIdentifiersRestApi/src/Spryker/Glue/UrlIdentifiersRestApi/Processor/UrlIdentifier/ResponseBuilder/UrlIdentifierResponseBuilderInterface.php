<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UrlIdentifiersRestApi\Processor\UrlIdentifier\ResponseBuilder;

use Generated\Shared\Transfer\RestUrlIdentifierAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface UrlIdentifierResponseBuilderInterface
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
     * @param string $urlIdentifierId
     * @param \Generated\Shared\Transfer\RestUrlIdentifierAttributesTransfer $restUrlIdentifierAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createUrlIdentifiersResourceResponse(string $urlIdentifierId, RestUrlIdentifierAttributesTransfer $restUrlIdentifierAttributesTransfer): RestResponseInterface;
}

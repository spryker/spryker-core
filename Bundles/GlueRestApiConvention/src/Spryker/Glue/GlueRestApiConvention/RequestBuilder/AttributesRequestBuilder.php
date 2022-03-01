<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\RequestBuilder;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueRestApiConvention\Dependency\Service\GlueRestApiConventionToUtilEncodingServiceInterface;

class AttributesRequestBuilder implements RequestBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueRestApiConvention\Dependency\Service\GlueRestApiConventionToUtilEncodingServiceInterface
     */
    protected GlueRestApiConventionToUtilEncodingServiceInterface $utilEncodingService;

    /**
     * @param \Spryker\Glue\GlueRestApiConvention\Dependency\Service\GlueRestApiConventionToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(GlueRestApiConventionToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function buildRequest(GlueRequestTransfer $glueRequestTransfer): GlueRequestTransfer
    {
        $content = $glueRequestTransfer->getContent();

        if (!$content) {
            return $glueRequestTransfer;
        }

        $decodedContent = $this->utilEncodingService->decodeJson($content, true);
        if (!$decodedContent || !isset($decodedContent['data'])) {
            return $glueRequestTransfer;
        }

        $glueRequestTransfer->setAttributes($decodedContent['data']);

        return $glueRequestTransfer;
    }
}

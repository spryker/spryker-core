<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Builder\Request;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueApplication\Dependency\Service\GlueApplicationToUtilEncodingServiceInterface;

class AttributesRequestBuilder implements RequestBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Dependency\Service\GlueApplicationToUtilEncodingServiceInterface
     */
    protected GlueApplicationToUtilEncodingServiceInterface $utilEncodingService;

    /**
     * @param \Spryker\Glue\GlueApplication\Dependency\Service\GlueApplicationToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(GlueApplicationToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * Specification:
     * - Sets `GlueRequestTransfer.attributes` in case the request has content.
     * - Ignores content if the content structure does not have `data` in body.
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function build(GlueRequestTransfer $glueRequestTransfer): GlueRequestTransfer
    {
        $content = $glueRequestTransfer->getContent();

        if (!$content) {
            return $glueRequestTransfer;
        }

        $decodedContent = $this->utilEncodingService->decodeJson($content, true);
        if (!$decodedContent) {
            return $glueRequestTransfer;
        }

        $glueRequestTransfer->setAttributes($decodedContent);

        return $glueRequestTransfer;
    }
}

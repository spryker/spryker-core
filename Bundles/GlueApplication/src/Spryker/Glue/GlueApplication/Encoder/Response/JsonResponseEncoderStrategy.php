<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Encoder\Response;

use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueApplication\Dependency\Service\GlueApplicationToUtilEncodingServiceInterface;

/**
 * @method \Spryker\Glue\GlueApplication\GlueApplicationFactory getFactory()
 */
class JsonResponseEncoderStrategy implements ResponseEncoderStrategyInterface
{
    /**
     * @var string
     */
    protected const ACCEPTED_CONTENT_TYPE = 'application/json';

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
     * {@inheritDoc}
     * - Return format that mean the JSON encoder can be used.
     *
     * @return string
     */
    public function getAcceptedType(): string
    {
        return static::ACCEPTED_CONTENT_TYPE;
    }

    /**
     * {@inheritDoc}
     * - Transforms given content into JSON format and sets to the `GlueResponseTransfer.content`.
     * - Sets `Content-Type=application/json` to the `GlueResponseTransfer.meta`.
     *
     * @param mixed $content
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function encode($content, GlueResponseTransfer $glueResponseTransfer): GlueResponseTransfer
    {
        $glueResponseTransfer->addMeta('Content-Type', static::ACCEPTED_CONTENT_TYPE);
        $glueResponseTransfer->setContent($this->utilEncodingService->encodeJson($content));

        return $glueResponseTransfer;
    }
}

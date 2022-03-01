<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueJsonApiConvention\Request;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueJsonApiConvention\Decoder\DecoderInterface;

class AttributesRequestBuilder implements RequestBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueJsonApiConvention\Decoder\DecoderInterface
     */
    protected DecoderInterface $decoder;

    /**
     * @param \Spryker\Glue\GlueJsonApiConvention\Decoder\DecoderInterface $decoder
     */
    public function __construct(DecoderInterface $decoder)
    {
        $this->decoder = $decoder;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function extract(GlueRequestTransfer $glueRequestTransfer): GlueRequestTransfer
    {
        if (!$glueRequestTransfer->getContent()) {
            return $glueRequestTransfer;
        }

        $decodedContent = $this->decoder->decode($glueRequestTransfer->getContent());
        if (!$decodedContent || !isset($decodedContent['data']) || !isset($decodedContent['data']['attributes'])) {
            return $glueRequestTransfer;
        }

        $glueRequestTransfer->setAttributes($decodedContent['data']['attributes']);

        return $glueRequestTransfer;
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Encoder\Response;

use Generated\Shared\Transfer\GlueResponseTransfer;

interface ResponseEncoderStrategyInterface
{
    /**
     * Specification:
     * - Return format the encoder can handle.
     *
     * @return string
     */
    public function getAcceptedType(): string;

    /**
     * Specification:
     * - Transforms given content into the format these encoder implements and sets to the `GlueResponseTransfer.content`.
     * - Sets format which encoder implements to the `GlueResponseTransfer.meta`
     *
     * @param mixed $content
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function encode($content, GlueResponseTransfer $glueResponseTransfer): GlueResponseTransfer;
}

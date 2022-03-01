<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueJsonApiConvention\Response;

use ArrayObject;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;

interface JsonGlueResponseFormatterInterface
{
    /**
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     * @param array<string, mixed> $sparseFields
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return string
     */
    public function formatResponseData(
        GlueResponseTransfer $glueResponseTransfer,
        array $sparseFields,
        GlueRequestTransfer $glueRequestTransfer
    ): string;

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return string
     */
    public function formatResponseWithEmptyResource(GlueRequestTransfer $glueRequestTransfer): string;

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\GlueErrorTransfer> $glueErrorTransfers
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return string
     */
    public function formatErrorResponse(ArrayObject $glueErrorTransfers, GlueRequestTransfer $glueRequestTransfer): string;
}

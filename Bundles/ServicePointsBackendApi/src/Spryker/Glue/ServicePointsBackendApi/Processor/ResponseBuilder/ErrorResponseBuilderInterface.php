<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder;

use ArrayObject;
use Generated\Shared\Transfer\GlueResponseTransfer;

interface ErrorResponseBuilderInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
     * @param string|null $localeName
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createErrorResponse(
        ArrayObject $errorTransfers,
        ?string $localeName = null
    ): GlueResponseTransfer;
}

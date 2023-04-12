<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder;

use ArrayObject;
use Generated\Shared\Transfer\GlueResponseTransfer;

interface ServicePointResponseBuilderInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointTransfer> $servicePointTransfers
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createServicePointResponse(
        ArrayObject $servicePointTransfers
    ): GlueResponseTransfer;

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
     * @param string|null $localeName
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createServicePointErrorResponse(
        ArrayObject $errorTransfers,
        ?string $localeName
    ): GlueResponseTransfer;
}

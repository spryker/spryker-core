<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PushNotificationsBackendApi\Processor\ResponseBuilder;

use ArrayObject;
use Generated\Shared\Transfer\GlueResponseTransfer;

interface ErrorResponseBuilderInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
     * @param string|null $localeName
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createErrorResponse(
        ArrayObject $errorTransfers,
        ?string $localeName = null
    ): GlueResponseTransfer;

    /**
     * @param string $errorMessage
     * @param string|null $localeName
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createErrorResponseFromErrorMessage(
        string $errorMessage,
        ?string $localeName = null
    ): GlueResponseTransfer;
}

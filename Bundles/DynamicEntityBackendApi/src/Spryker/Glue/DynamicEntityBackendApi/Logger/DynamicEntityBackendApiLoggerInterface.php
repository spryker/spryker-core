<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DynamicEntityBackendApi\Logger;

use Exception;
use Generated\Shared\Transfer\GlueRequestTransfer;

interface DynamicEntityBackendApiLoggerInterface
{
    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return void
     */
    public function logInfo(GlueRequestTransfer $glueRequestTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Exception $exception
     *
     * @return void
     */
    public function logError(GlueRequestTransfer $glueRequestTransfer, Exception $exception): void;
}

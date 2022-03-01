<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueHttp\Response;

use Generated\Shared\Transfer\GlueResponseTransfer;

interface HttpSenderInterface
{
    /**
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     *
     * @return void
     */
    public function sendResponse(GlueResponseTransfer $glueResponseTransfer): void;
}

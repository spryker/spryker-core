<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Http\Response;

use Generated\Shared\Transfer\GlueResponseTransfer;
use Symfony\Component\HttpFoundation\Response;

interface HttpSenderInterface
{
    /**
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sendResponse(GlueResponseTransfer $glueResponseTransfer): Response;
}

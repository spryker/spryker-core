<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueHttp\GlueContext;

use Generated\Shared\Transfer\GlueApiContextTransfer;
use Symfony\Component\HttpFoundation\Request;

class GlueContextHttpExpander implements GlueContextHttpExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\GlueApiContextTransfer $glueApiContextTransfer
     *
     * @return \Generated\Shared\Transfer\GlueApiContextTransfer
     */
    public function expand(GlueApiContextTransfer $glueApiContextTransfer): GlueApiContextTransfer
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $glueApiContextTransfer->setHost($request->getHost());
        $glueApiContextTransfer->setPath($request->getPathInfo());
        $glueApiContextTransfer->setMethod($request->getMethod());

        return $glueApiContextTransfer;
    }
}

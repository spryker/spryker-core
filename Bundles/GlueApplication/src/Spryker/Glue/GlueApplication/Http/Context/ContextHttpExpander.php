<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Http\Context;

use Generated\Shared\Transfer\GlueApiContextTransfer;
use Symfony\Component\HttpFoundation\Request;

class ContextHttpExpander implements ContextHttpExpanderInterface
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueApiContextTransfer $glueApiContextTransfer
     *
     * @return \Generated\Shared\Transfer\GlueApiContextTransfer
     */
    public function expand(GlueApiContextTransfer $glueApiContextTransfer): GlueApiContextTransfer
    {
        $glueApiContextTransfer->setHost($this->request->getHost());
        $glueApiContextTransfer->setPath($this->request->getPathInfo());
        $glueApiContextTransfer->setMethod($this->request->getMethod());

        return $glueApiContextTransfer;
    }
}

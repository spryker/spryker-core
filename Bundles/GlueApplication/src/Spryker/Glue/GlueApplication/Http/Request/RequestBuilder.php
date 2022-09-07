<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Http\Request;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Symfony\Component\HttpFoundation\Request;

class RequestBuilder implements RequestBuilderInterface
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
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function extract(GlueRequestTransfer $glueRequestTransfer): GlueRequestTransfer
    {
        if ($this->request->getContent()) {
            $glueRequestTransfer->setContent((string)$this->request->getContent());
        }

        if ($this->request->headers->all()) {
            $glueRequestTransfer->setMeta($this->request->headers->all());
        }

        if ($this->request->query->all()) {
            $glueRequestTransfer->setQueryFields($this->request->query->all());
        }

        if ($this->request->attributes->all()) {
            $glueRequestTransfer->setHttpRequestAttributes($this->request->attributes->all());
        }

        if ($this->request->request->all()) {
            $glueRequestTransfer->setAttributes($this->request->request->all());
        }

        $glueRequestTransfer->setHost($this->request->getHost());
        $glueRequestTransfer->setPath($this->request->getPathInfo());
        $glueRequestTransfer->setMethod($this->request->getMethod());
        $glueRequestTransfer->setParametersString($this->request->getQueryString());

        return $glueRequestTransfer;
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Http\Response;

use Generated\Shared\Transfer\GlueResponseTransfer;
use Symfony\Component\HttpFoundation\Response;

class HttpSender implements HttpSenderInterface
{
    /**
     * @var \Symfony\Component\HttpFoundation\Response
     */
    protected $response;

    /**
     * @param \Symfony\Component\HttpFoundation\Response $response
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     *
     * @return void
     */
    public function sendResponse(GlueResponseTransfer $glueResponseTransfer): void
    {
        $this->response->setContent($glueResponseTransfer->getContent());
        $this->response->headers->add($glueResponseTransfer->getMeta());
        $this->response->setStatusCode($glueResponseTransfer->getHttpStatusOrFail());
        if ($glueResponseTransfer->getFormat()) {
            $this->response->headers->set('Content-Type', $glueResponseTransfer->getFormat());
        }

        $this->response->send();
    }
}

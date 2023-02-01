<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\Testify\Helper\Stub;

use Spryker\Glue\GlueApplication\Http\Response\HttpSender;
use Symfony\Component\HttpFoundation\Response;

/**
 * This class is used as stub where we disable the constructor to be able to get the Response in a test.
 */
class HttpSenderStub extends HttpSender
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }
}

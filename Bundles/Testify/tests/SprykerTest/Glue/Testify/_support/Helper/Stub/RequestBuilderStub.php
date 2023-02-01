<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\Testify\Helper\Stub;

use Spryker\Glue\GlueApplication\Http\Request\RequestBuilder;
use Symfony\Component\HttpFoundation\Request;

/**
 * This class is used as stub where we disable the constructor to be able to pass in a prepared Request from a test.
 */
class RequestBuilderStub extends RequestBuilder
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }
}

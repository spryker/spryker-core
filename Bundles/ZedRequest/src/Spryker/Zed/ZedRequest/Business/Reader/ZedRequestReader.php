<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedRequest\Business\Reader;

use Spryker\Shared\ZedRequest\Client\AbstractRequest;
use Spryker\Zed\ZedRequest\Business\Client\Request as ZedRequest;
use Symfony\Component\HttpFoundation\Request;

class ZedRequestReader implements ZedRequestReaderInterface
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
     * @return \Spryker\Shared\ZedRequest\Client\AbstractRequest
     */
    public function getCurrentZedRequest(): AbstractRequest
    {
        /** @phpstan-var string */
        $content = $this->request->getContent();
        $transferValues = json_decode($content, true);

        return new ZedRequest($transferValues);
    }
}

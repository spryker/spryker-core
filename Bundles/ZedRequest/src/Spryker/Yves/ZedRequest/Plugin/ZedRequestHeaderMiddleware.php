<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\ZedRequest\Plugin;

use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Spryker\Shared\Application\Log\Request\RequestId;
use Spryker\Shared\ZedRequest\Client\Middleware\MiddlewareInterface;

class ZedRequestHeaderMiddleware implements MiddlewareInterface
{

    /**
     * @return string
     */
    public function getName()
    {
        return static::class;
    }

    /**
     * @return callable
     */
    public function getCallable()
    {
        return Middleware::mapRequest(function (RequestInterface $request) {
            if ($request->hasHeader('X-Yves-Host')) {
                $requestId = new RequestId();
                $request = $request->withAddedHeader('X-Request-ID', $requestId->getRequestId());
            }

            return $request;
        });
    }

}

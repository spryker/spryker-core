<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\ZedRequest\Plugin;

use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Spryker\Shared\ZedRequest\Client\Middleware\MiddlewareInterface;
use Spryker\Yves\ZedRequest\Dependency\Service\ZedRequestToUtilNetworkInterface;

class ZedRequestHeaderMiddleware implements MiddlewareInterface
{

    /**
     * @var \Spryker\Yves\ZedRequest\Dependency\Service\ZedRequestToUtilNetworkInterface
     */
    protected $utilNetworkService;

    /**
     * @param \Spryker\Yves\ZedRequest\Dependency\Service\ZedRequestToUtilNetworkInterface $utilNetworkService
     */
    public function __construct(ZedRequestToUtilNetworkInterface $utilNetworkService)
    {
        $this->utilNetworkService = $utilNetworkService;
    }

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
                $request = $request->withAddedHeader('X-Request-ID', $this->utilNetworkService->getRequestId());
            }

            return $request;
        });
    }

}

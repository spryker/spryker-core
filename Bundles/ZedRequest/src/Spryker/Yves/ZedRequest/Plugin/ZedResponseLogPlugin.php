<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\ZedRequest\Plugin;

use GuzzleHttp\Middleware;
use Psr\Http\Message\ResponseInterface;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Shared\ZedRequest\Client\AbstractHttpClient;
use Spryker\Shared\ZedRequest\Client\Middleware\MiddlewareInterface;

class ZedResponseLogPlugin implements MiddlewareInterface
{
    use LoggerTrait;

    public const NAME = 'guzzle response log middleware';

    /**
     * @return string
     */
    public function getName()
    {
        return static::NAME;
    }

    /**
     * @return callable
     */
    public function getCallable()
    {
        return Middleware::mapResponse(function (ResponseInterface $response) {
            if ($response->hasHeader(AbstractHttpClient::HEADER_HOST_ZED)) {
                $message = sprintf(
                    'Transfer response [%s]',
                    $response->getStatusCode()
                );
                $this->getLogger()->info($message, ['guzzle-body' => $response->getBody()->getContents()]);
            }

            return $response;
        });
    }
}

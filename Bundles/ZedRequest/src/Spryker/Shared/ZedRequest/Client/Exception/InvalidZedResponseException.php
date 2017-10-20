<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ZedRequest\Client\Exception;

use Psr\Http\Message\ResponseInterface;
use RuntimeException;

class InvalidZedResponseException extends RuntimeException
{
    /**
     * @param string $reason
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param string $url
     */
    public function __construct($reason, ResponseInterface $response, $url)
    {
        $message = 'Invalid response from Zed' . PHP_EOL . implode(PHP_EOL, [
            '[status code] ' . $response->getStatusCode(),
            '[reason phrase] ' . $reason,
            '[url] ' => $url,
            '[raw body] ' . htmlentities(substr($response->getBody(), 0, 80)) . '...',
        ]);

        parent::__construct($message);
    }
}

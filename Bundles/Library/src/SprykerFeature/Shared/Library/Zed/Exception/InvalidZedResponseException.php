<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Zed\Exception;

use Guzzle\Http\Message\Response;

class InvalidZedResponseException extends \RuntimeException
{

    public function __construct($reason, Response $response)
    {
        $message = 'Invalid response from Zed' . PHP_EOL . implode(PHP_EOL, [
            '[status code] ' . $response->getStatusCode(),
            '[reason phrase] ' . $reason,
            '[url] ' . $response->getEffectiveUrl(),
            '[raw body] ' . htmlentities(substr($response->getBody(true), 0, 80)) . '...',
        ]);

        parent::__construct($message);
    }

}

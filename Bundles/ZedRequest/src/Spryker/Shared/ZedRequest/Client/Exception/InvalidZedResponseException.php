<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ZedRequest\Client\Exception;

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

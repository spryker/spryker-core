<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library\Zed\Exception;

use Psr\Http\Message\ResponseInterface;

/**
 * @deprecated Moved to ZedRequest Bundle
 */
class InvalidZedResponseException extends \RuntimeException
{

    public function __construct($reason, ResponseInterface $response)
    {
        $message = 'Invalid response from Zed' . PHP_EOL . implode(PHP_EOL, [
            '[status code] ' . $response->getStatusCode(),
            '[reason phrase] ' . $reason,
            '[raw body] ' . htmlentities(substr($response->getBody(), 0, 80)) . '...',
        ]);

        parent::__construct($message);
    }

}

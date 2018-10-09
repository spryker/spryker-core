<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilNetwork\Model\Request;

class RequestId implements RequestIdInterface
{
    public const REQUEST_ID_HEADER_KEY = 'HTTP_X_REQUEST_ID';

    /**
     * @var string
     */
    protected static $requestId;

    /**
     * @return string
     */
    public function getRequestId()
    {
        if (!static::$requestId) {
            static::$requestId = $this->createRequestId();
        }

        return static::$requestId;
    }

    /**
     * @return string
     */
    protected function createRequestId()
    {
        if (isset($_SERVER[static::REQUEST_ID_HEADER_KEY])) {
            return $_SERVER[static::REQUEST_ID_HEADER_KEY];
        }

        $requestId = $this->generateRandomString(8);

        return $requestId;
    }

    /**
     * @param int $length
     *
     * @return string
     */
    protected function generateRandomString($length = 32)
    {
        $tokenLength = $length / 2;
        $token = bin2hex(random_bytes($tokenLength));

        if (strlen($token) !== $length) {
            $token = str_pad($token, $length, '0');
        }

        return $token;
    }
}

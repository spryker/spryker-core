<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Application\Log\Request;

use Spryker\Zed\Library\Generator\StringGenerator;

class RequestId
{

    const REQUEST_ID_HEADER_KEY = 'HTTP_X_REQUEST_ID';

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

        $stringGenerator = new StringGenerator();
        $requestId = $stringGenerator
            ->setLength(8)
            ->generateRandomString();

        return $requestId;
    }

}

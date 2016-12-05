<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Application\Log\Request;

use Spryker\Service\UtilText\UtilTextService;

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

        $utilTextService = new UtilTextService();
        $requestId = $utilTextService->generateRandomString(8);

        return $requestId;
    }

}

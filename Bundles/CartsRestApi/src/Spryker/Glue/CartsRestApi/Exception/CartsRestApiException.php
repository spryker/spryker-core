<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Exception;

use Exception;
use Throwable;

class CartsRestApiException extends Exception
{
    /**
     * @var string
     */
    protected $errorCode;

    /**
     * @param string $message
     * @param int $code
     * @param string $errorCode
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = "", int $code = 0, string $errorCode = '', ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->errorCode = $errorCode;
    }

    /**
     * @return string
     */
    public function getErrorCode(): string
    {
        return $this->errorCode;
    }
}

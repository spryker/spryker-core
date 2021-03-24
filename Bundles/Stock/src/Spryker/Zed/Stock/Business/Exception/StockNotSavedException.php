<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Business\Exception;

use ArrayObject;
use Exception;
use Throwable;

class StockNotSavedException extends Exception
{
    /**
     * @var \ArrayObject|\Generated\Shared\Transfer\MessageTransfer[]
     */
    protected $errorMessages;

    /**
     * @param \ArrayObject $errorMessages
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(
        ArrayObject $errorMessages,
        string $message = '',
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);

        $this->errorMessages = $errorMessages;
    }

    /**
     * @return \ArrayObject|\Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getErrorMessages()
    {
        return $this->errorMessages;
    }
}

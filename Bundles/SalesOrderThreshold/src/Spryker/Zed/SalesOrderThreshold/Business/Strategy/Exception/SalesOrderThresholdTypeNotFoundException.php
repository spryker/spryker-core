<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Business\Strategy\Exception;

use Exception;
use Throwable;

class SalesOrderThresholdTypeNotFoundException extends Exception
{
    protected const MESSAGE = 'No strategy was found for the key `%s`';

    /**
     * @param string $salesOrderThresholdTypeKey
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $salesOrderThresholdTypeKey, string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        $this->message = sprintf(static::MESSAGE, $salesOrderThresholdTypeKey);

        parent::__construct($message, $code, $previous);
    }
}

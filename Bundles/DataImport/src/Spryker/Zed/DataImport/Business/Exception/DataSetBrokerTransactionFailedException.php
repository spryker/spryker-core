<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Exception;

use Exception;
use Throwable;

class DataSetBrokerTransactionFailedException extends Exception implements TransactionRolledBackAwareExceptionInterface
{
    /**
     * @var int
     */
    protected $rolledBackRowsCount = 0;

    /**
     * @param int $rolledBackRowsCount
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(int $rolledBackRowsCount, $message = "", $code = 0, ?Throwable $previous = null)
    {
        $this->rolledBackRowsCount = $rolledBackRowsCount;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return int
     */
    public function getRolledBackRowsCount(): int
    {
        return $this->rolledBackRowsCount;
    }
}

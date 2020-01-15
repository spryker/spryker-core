<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business\Exception;

use ArrayObject;
use Exception;
use Throwable;

class MerchantNotSavedException extends Exception
{
    /**
     * @var \ArrayObject
     */
    protected $merchantErrorTransfers;

    /**
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->merchantErrorTransfers = new ArrayObject();
    }

    /**
     * @return \ArrayObject
     */
    public function getErrors(): ArrayObject
    {
        return $this->merchantErrorTransfers;
    }

    /**
     * @param \ArrayObject $merchantErrorTransfers
     *
     * @return $this
     */
    public function addErrors(ArrayObject $merchantErrorTransfers)
    {
        $this->merchantErrorTransfers = $merchantErrorTransfers;

        return $this;
    }
}

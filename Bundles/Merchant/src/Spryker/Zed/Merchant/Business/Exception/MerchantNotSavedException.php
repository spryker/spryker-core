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
     * @var \ArrayObject|\Generated\Shared\Transfer\MerchantErrorTransfer[]
     */
    protected $merchantErrorTransfers;

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\MerchantErrorTransfer[] $merchantErrorTransfers
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(
        ArrayObject $merchantErrorTransfers,
        string $message = '',
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);

        $this->merchantErrorTransfers = $merchantErrorTransfers;
    }

    /**
     * @return \ArrayObject|\Generated\Shared\Transfer\MerchantErrorTransfer[]
     */
    public function getErrors(): ArrayObject
    {
        return $this->merchantErrorTransfers;
    }
}

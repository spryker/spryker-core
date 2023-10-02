<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ServicePointCart\MessageAdder;

use ArrayObject;

interface MessageAdderInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\QuoteErrorTransfer> $quoteErrorTransfers
     *
     * @return void
     */
    public function addQuoteResponseErrors(ArrayObject $quoteErrorTransfers): void;
}

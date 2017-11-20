<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Ratepay\Business\Request\RequestMethodInterface;

interface QuoteTransactionInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function request(QuoteTransfer $quoteTransfer);

    /**
     * @param \Spryker\Zed\Ratepay\Business\Request\RequestMethodInterface $mapper
     *
     * @return void
     */
    public function registerMethodMapper(RequestMethodInterface $mapper);
}

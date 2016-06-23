<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Order\MethodMapper;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Ratepay\RatepayConstants;

class Prepayment extends AbstractMapper
{

    /**
     * @const string Method name.
     */
    const METHOD = RatepayConstants::METHOD_PREPAYMENT;

    /**
     * @return string
     */
    public function getMethodName()
    {
        return static::METHOD;
    }

    /**
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\RatepayPaymentPrepaymentTransfer
     */
    protected function getPaymentTransfer(QuoteTransfer $quoteTransfer)
    {
        return $quoteTransfer->getPayment()->getRatepayPrepayment();
    }

}

<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Mapper;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RatepayRequestPaymentTransfer;
use Generated\Shared\Transfer\RatepayRequestTransfer;
use Spryker\Shared\Ratepay\RatepayConstants;
use Spryker\Shared\Transfer\TransferInterface;

class PaymentMapper extends BaseMapper
{

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $quoteTransfer;

    /**
     * @var \Generated\Shared\Transfer\RatepayPaymentElvTransfer|\Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer
     */
    protected $ratepayPaymentTransfer;

    /**
     * @var \Generated\Shared\Transfer\RatepayRequestTransfer
     */
    protected $requestTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Transfer\TransferInterface $ratepayPaymentTransfer
     * @param \Generated\Shared\Transfer\RatepayRequestTransfer $requestTransfer
     */
    public function __construct(
        QuoteTransfer $quoteTransfer,
        TransferInterface $ratepayPaymentTransfer,
        RatepayRequestTransfer $requestTransfer
    ) {

        $this->quoteTransfer = $quoteTransfer;
        $this->ratepayPaymentTransfer = $ratepayPaymentTransfer;
        $this->requestTransfer = $requestTransfer;
    }

    /**
     * @return void
     */
    public function map()
    {
        $paymentMethod = $this->ratepayPaymentTransfer->requirePaymentType()->getPaymentType();
        $totalsTransfer = $this->quoteTransfer->requireTotals()->getTotals();
        $grandTotal = $this->centsToDecimal($totalsTransfer->requireGrandTotal()->getGrandTotal());

        $this->requestTransfer->setPayment(new RatepayRequestPaymentTransfer())->getPayment()
            ->setCurrency($this->ratepayPaymentTransfer->requireCurrencyIso3()->getCurrencyIso3())
            ->setMethod($paymentMethod)
            ->setAmount($grandTotal);
    }

}

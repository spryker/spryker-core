<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Mapper;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer;
use Generated\Shared\Transfer\RatepayRequestInstallmentPaymentTransfer;
use Generated\Shared\Transfer\RatepayRequestTransfer;

class InstallmentPaymentMapper extends BaseMapper
{

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $quoteTransfer;

    /**
     * @var \Generated\Shared\Transfer\RatepayRequestTransfer
     */
    protected $requestTransfer;

    /**
     * @var \Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer
     */
    protected $ratepayPaymentTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer $ratepayPaymentTransfer
     * @param \Generated\Shared\Transfer\RatepayRequestTransfer $requestTransfer
     */
    public function __construct(
        QuoteTransfer $quoteTransfer,
        RatepayPaymentInstallmentTransfer $ratepayPaymentTransfer,
        RatepayRequestTransfer $requestTransfer
    ) {

        $this->quoteTransfer = $quoteTransfer;
        $this->requestTransfer = $requestTransfer;
        $this->ratepayPaymentTransfer = $ratepayPaymentTransfer;
    }

    /**
     * @return void
     */
    public function map()
    {
        $this->requestTransfer->setInstallmentPayment(new RatepayRequestInstallmentPaymentTransfer())->getInstallmentPayment()
            ->setDebitPayType($this->ratepayPaymentTransfer->getDebitPayType())
            ->setAmount(
                $this->centsToDecimal(
                    $this->quoteTransfer
                        ->getPayment()
                        ->getRatepayInstallment()
                        ->getInstallmentGrandTotalAmount()
                )
            );
    }

}

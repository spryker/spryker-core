<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Mapper;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RatepayRequestInstallmentCalculationTransfer;
use Generated\Shared\Transfer\RatepayRequestTransfer;

class InstallmentCalculationMapper extends BaseMapper
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
     * @param \Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer $ratepayPaymentTransfer
     * @param \Generated\Shared\Transfer\RatepayRequestTransfer $requestTransfer
     */
    public function __construct(
        QuoteTransfer $quoteTransfer,
        $ratepayPaymentTransfer,
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
        $grandTotal = $this->centsToDecimal(
            $this->quoteTransfer->requireTotals()
                ->getTotals()
                ->requireGrandTotal()
                ->getGrandTotal()
        );
        $this->requestTransfer->setInstallmentCalculation(new RatepayRequestInstallmentCalculationTransfer())->getInstallmentCalculation()
            ->setSubType($this->ratepayPaymentTransfer->getInstallmentCalculationType())
            ->setAmount($grandTotal)
            ->setCalculationRate($this->ratepayPaymentTransfer->getInterestRate())
            ->setMonth($this->ratepayPaymentTransfer->getInterestMonth())
            ->setPaymentFirstday($this->ratepayPaymentTransfer->getInstallmentPaymentFirstDay())
            ->setCalculationStart($this->ratepayPaymentTransfer->getInstallmentCalculationStart());

        $this->requestTransfer->getHead()
            ->setOperationSubstring($this->ratepayPaymentTransfer->getInstallmentCalculationType());
    }

}

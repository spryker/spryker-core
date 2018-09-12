<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Mapper;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RatepayRequestInstallmentCalculationTransfer;
use Generated\Shared\Transfer\RatepayRequestTransfer;
use Spryker\Zed\Ratepay\Dependency\Facade\RatepayToMoneyInterface;

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
     * @var \Spryker\Zed\Ratepay\Dependency\Facade\RatepayToMoneyInterface
     */
    protected $moneyFacade;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RatepayPaymentElvTransfer|\Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer $ratepayPaymentTransfer
     * @param \Generated\Shared\Transfer\RatepayRequestTransfer $requestTransfer
     * @param \Spryker\Zed\Ratepay\Dependency\Facade\RatepayToMoneyInterface $moneyFacade
     */
    public function __construct(
        QuoteTransfer $quoteTransfer,
        $ratepayPaymentTransfer,
        RatepayRequestTransfer $requestTransfer,
        RatepayToMoneyInterface $moneyFacade
    ) {
        $this->quoteTransfer = $quoteTransfer;
        $this->ratepayPaymentTransfer = $ratepayPaymentTransfer;
        $this->requestTransfer = $requestTransfer;
        $this->moneyFacade = $moneyFacade;
    }

    /**
     * @return void
     */
    public function map()
    {
        $grandTotal = $this->moneyFacade->convertIntegerToDecimal((int)$this->quoteTransfer->requireTotals()
                ->getTotals()
                ->requireGrandTotal()
                ->getGrandTotal());
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

<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Mapper;

use Generated\Shared\Transfer\RatepayPaymentRequestTransfer;
use Generated\Shared\Transfer\RatepayRequestInstallmentDetailsTransfer;
use Generated\Shared\Transfer\RatepayRequestTransfer;

class InstallmentDetailMapper extends BaseMapper
{

    /**
     * @var \Generated\Shared\Transfer\RatepayPaymentRequestTransfer
     */
    protected $ratepayPaymentRequestTransfer;

    /**
     * @var \Generated\Shared\Transfer\RatepayRequestTransfer
     */
    protected $requestTransfer;

    /**
     * @param \Generated\Shared\Transfer\RatepayPaymentRequestTransfer $ratepayPaymentRequestTransfer
     * @param \Generated\Shared\Transfer\RatepayRequestTransfer $requestTransfer
     */
    public function __construct(
        RatepayPaymentRequestTransfer $ratepayPaymentRequestTransfer,
        RatepayRequestTransfer $requestTransfer
    ) {
        $this->ratepayPaymentRequestTransfer = $ratepayPaymentRequestTransfer;
        $this->requestTransfer = $requestTransfer;
    }

    /**
     * @return void
     */
    public function map()
    {
        $this->requestTransfer->setInstallmentDetails(new RatepayRequestInstallmentDetailsTransfer())->getInstallmentDetails()
            ->setRatesNumber($this->ratepayPaymentRequestTransfer->getInstallmentNumberRates())
            ->setAmount($this->centsToDecimal($this->ratepayPaymentRequestTransfer->getInstallmentRate()))
            ->setLastAmount($this->centsToDecimal($this->ratepayPaymentRequestTransfer->getInstallmentLastRate()))
            ->setInterestRate($this->centsToDecimal($this->ratepayPaymentRequestTransfer->getInstallmentInterestRate()))
            ->setPaymentFirstday($this->ratepayPaymentRequestTransfer->getInstallmentPaymentFirstDay());
    }

}

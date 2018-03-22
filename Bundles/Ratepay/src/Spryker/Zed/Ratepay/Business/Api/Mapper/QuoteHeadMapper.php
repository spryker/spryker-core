<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Mapper;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RatepayRequestHeadTransfer;
use Generated\Shared\Transfer\RatepayRequestTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Ratepay\RatepayConfig;

class QuoteHeadMapper extends BaseMapper
{
    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $quoteTransfer;

    /**
     * @var \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    protected $paymentData;

    /**
     * @var \Spryker\Zed\Ratepay\RatepayConfig
     */
    protected $config;

    /**
     * @var \Generated\Shared\Transfer\RatepayRequestTransfer
     */
    protected $requestTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $paymentData
     * @param \Spryker\Zed\Ratepay\RatepayConfig $config
     * @param \Generated\Shared\Transfer\RatepayRequestTransfer $requestTransfer
     */
    public function __construct(
        QuoteTransfer $quoteTransfer,
        TransferInterface $paymentData,
        RatepayConfig $config,
        RatepayRequestTransfer $requestTransfer
    ) {
        $this->quoteTransfer = $quoteTransfer;
        $this->paymentData = $paymentData;
        $this->config = $config;
        $this->requestTransfer = $requestTransfer;
    }

    /**
     * @return void
     */
    public function map()
    {
        $this->requestTransfer->setHead(new RatepayRequestHeadTransfer())->getHead()
            ->setTransactionId($this->paymentData->getTransactionId())
            ->setTransactionShortId($this->paymentData->getTransactionShortId())
            ->setCustomerId($this->quoteTransfer->getCustomer()->getIdCustomer())
            ->setDeviceFingerprint($this->paymentData->getDeviceFingerprint())
            ->setSystemId($this->config->getSystemId())
            ->setProfileId($this->config->getProfileId())
            ->setSecurityCode($this->config->getSecurityCode());
    }
}

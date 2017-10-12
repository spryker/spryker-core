<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Mapper;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RatepayRequestHeadTransfer;
use Generated\Shared\Transfer\RatepayRequestTransfer;
use Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay;
use Spryker\Zed\Ratepay\RatepayConfig;

class OrderHeadMapper extends BaseMapper
{
    /**
     * @var \Generated\Shared\Transfer\OrderTransfer
     */
    protected $orderTransfer;

    /**
     * @var \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay
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
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay $paymentData
     * @param \Spryker\Zed\Ratepay\RatepayConfig $config
     * @param \Generated\Shared\Transfer\RatepayRequestTransfer $requestTransfer
     */
    public function __construct(
        OrderTransfer $orderTransfer,
        SpyPaymentRatepay $paymentData,
        RatepayConfig $config,
        RatepayRequestTransfer $requestTransfer
    ) {

        $this->orderTransfer = $orderTransfer;
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

            ->setOrderId($this->orderTransfer->getIdSalesOrder())
            ->setExternalOrderId($this->orderTransfer->getOrderReference())

            ->setSystemId($this->config->getSystemId())
            ->setProfileId($this->config->getProfileId())
            ->setSecurityCode($this->config->getSecurityCode());
    }
}

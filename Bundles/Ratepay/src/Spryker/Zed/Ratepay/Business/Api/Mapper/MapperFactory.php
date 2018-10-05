<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Mapper;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RatepayPaymentInitTransfer;
use Generated\Shared\Transfer\RatepayPaymentRequestTransfer;
use Generated\Shared\Transfer\RatepayRequestTransfer;
use Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Ratepay\RatepayDependencyProvider;

/**
 * @method \Spryker\Zed\Ratepay\RatepayConfig getConfig()
 */
class MapperFactory extends AbstractBusinessFactory
{
    /**
     * @var \Generated\Shared\Transfer\RatepayRequestTransfer
     */
    protected $requestTransfer;

    /**
     * @param \Generated\Shared\Transfer\RatepayRequestTransfer $requestTransfer
     */
    public function __construct(RatepayRequestTransfer $requestTransfer)
    {
        $this->requestTransfer = $requestTransfer;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Dependency\Facade\RatepayToMoneyInterface
     */
    protected function getMoneyFacade()
    {
        return $this->getProvidedDependency(RatepayDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Mapper\HeadMapper
     */
    public function getHeadMapper()
    {
        return new HeadMapper(
            $this->getConfig(),
            $this->requestTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RatepayPaymentInitTransfer $ratepayPaymentInitTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Mapper\PaymentInitHeadMapper
     */
    public function getPaymentInitHeadMapper(
        RatepayPaymentInitTransfer $ratepayPaymentInitTransfer
    ) {
        return new PaymentInitHeadMapper(
            $ratepayPaymentInitTransfer,
            $this->getConfig(),
            $this->requestTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $paymentData
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Mapper\QuoteHeadMapper
     */
    public function getQuoteHeadMapper(
        QuoteTransfer $quoteTransfer,
        TransferInterface $paymentData
    ) {
        return new QuoteHeadMapper(
            $quoteTransfer,
            $paymentData,
            $this->getConfig(),
            $this->requestTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay $paymentData
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Mapper\OrderHeadMapper
     */
    public function getOrderHeadMapper(
        OrderTransfer $orderTransfer,
        SpyPaymentRatepay $paymentData
    ) {
        return new OrderHeadMapper(
            $orderTransfer,
            $paymentData,
            $this->getConfig(),
            $this->requestTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param string $type
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Mapper\AddressMapper
     */
    public function getAddressMapper(
        AddressTransfer $addressTransfer,
        $type
    ) {
        return new AddressMapper(
            $addressTransfer,
            $type,
            $this->requestTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RatepayPaymentRequestTransfer $ratepayPaymentRequestTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Mapper\BankAccountMapper
     */
    public function getBankAccountMapper(
        RatepayPaymentRequestTransfer $ratepayPaymentRequestTransfer
    ) {
        return new BankAccountMapper(
            $ratepayPaymentRequestTransfer,
            $this->requestTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Mapper\BasketItemMapper
     */
    public function getBasketItemMapper(
        ItemTransfer $itemTransfer
    ) {
        return new BasketItemMapper(
            $itemTransfer,
            $this->requestTransfer,
            $this->getMoneyFacade()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RatepayPaymentRequestTransfer $ratepayPaymentRequestTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Mapper\BasketMapper
     */
    public function getBasketMapper(
        RatepayPaymentRequestTransfer $ratepayPaymentRequestTransfer
    ) {
        return new BasketMapper(
            $ratepayPaymentRequestTransfer,
            $this->requestTransfer,
            $this->getMoneyFacade()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $partialOrderTransfer
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $ratepayPaymentTransfer
     * @param bool $needToSendShipping
     * @param float|int $discountTaxRate
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Mapper\PartialBasketMapper
     */
    public function getPartialBasketMapper(
        OrderTransfer $orderTransfer,
        OrderTransfer $partialOrderTransfer,
        $ratepayPaymentTransfer,
        $needToSendShipping = false,
        $discountTaxRate = 0
    ) {
        return new PartialBasketMapper(
            $orderTransfer,
            $partialOrderTransfer,
            $ratepayPaymentTransfer,
            $needToSendShipping,
            $discountTaxRate,
            $this->requestTransfer,
            $this->getMoneyFacade()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RatepayPaymentRequestTransfer $ratepayPaymentRequestTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Mapper\CustomerMapper
     */
    public function getCustomerMapper(
        RatepayPaymentRequestTransfer $ratepayPaymentRequestTransfer
    ) {
        return new CustomerMapper(
            $ratepayPaymentRequestTransfer,
            $this->requestTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RatepayPaymentRequestTransfer $ratepayPaymentRequestTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Mapper\PaymentMapper
     */
    public function getPaymentMapper(
        RatepayPaymentRequestTransfer $ratepayPaymentRequestTransfer
    ) {
        return new PaymentMapper(
            $ratepayPaymentRequestTransfer,
            $this->requestTransfer,
            $this->getMoneyFacade()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RatepayPaymentElvTransfer|\Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer $ratepayPaymentTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Mapper\InstallmentCalculationMapper
     */
    public function getInstallmentCalculationMapper(
        QuoteTransfer $quoteTransfer,
        $ratepayPaymentTransfer
    ) {
        return new InstallmentCalculationMapper(
            $quoteTransfer,
            $ratepayPaymentTransfer,
            $this->requestTransfer,
            $this->getMoneyFacade()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RatepayPaymentRequestTransfer $ratepayPaymentRequestTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Mapper\InstallmentDetailMapper
     */
    public function getInstallmentDetailMapper(
        RatepayPaymentRequestTransfer $ratepayPaymentRequestTransfer
    ) {
        return new InstallmentDetailMapper(
            $ratepayPaymentRequestTransfer,
            $this->requestTransfer,
            $this->getMoneyFacade()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RatepayPaymentRequestTransfer $ratepayPaymentRequestTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Mapper\InstallmentPaymentMapper
     */
    public function getInstallmentPaymentMapper(
        RatepayPaymentRequestTransfer $ratepayPaymentRequestTransfer
    ) {
        return new InstallmentPaymentMapper(
            $ratepayPaymentRequestTransfer,
            $this->requestTransfer,
            $this->getMoneyFacade()
        );
    }
}

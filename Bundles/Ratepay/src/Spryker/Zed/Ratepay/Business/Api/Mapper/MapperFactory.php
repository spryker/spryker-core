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
use Generated\Shared\Transfer\RatepayRequestTransfer;
use Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay;
use Spryker\Shared\Transfer\TransferInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

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
     * @return \Spryker\Zed\Ratepay\Business\Api\Mapper\QuoteHeadMapper
     */
    public function getHeadMapper()
    {
        return new HeadMapper(
            $this->getConfig(),
            $this->requestTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Transfer\TransferInterface $paymentData
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Transfer\TransferInterface $ratepayPaymentTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Mapper\BankAccountMapper
     */
    public function getBankAccountMapper(
        QuoteTransfer $quoteTransfer,
        $ratepayPaymentTransfer
    ) {
        return new BankAccountMapper(
            $quoteTransfer,
            $ratepayPaymentTransfer,
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
            $this->requestTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Transfer\TransferInterface $ratepayPaymentTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Mapper\BasketMapper
     */
    public function getBasketMapper(
        $quoteTransfer,
        $ratepayPaymentTransfer
    ) {
        return new BasketMapper(
            $quoteTransfer,
            $ratepayPaymentTransfer,
            $this->requestTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Transfer\TransferInterface $ratepayPaymentTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer[] $orderItems
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Mapper\PartialBasketMapper
     */
    public function getPartialBasketMapper(
        $quoteTransfer,
        $ratepayPaymentTransfer,
        array $orderItems
    ) {
        return new PartialBasketMapper(
            $quoteTransfer,
            $ratepayPaymentTransfer,
            $orderItems,
            $this->requestTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Transfer\TransferInterface $ratepayPaymentTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Mapper\CustomerMapper
     */
    public function getCustomerMapper(
        QuoteTransfer $quoteTransfer,
        $ratepayPaymentTransfer
    ) {
        return new CustomerMapper(
            $quoteTransfer,
            $ratepayPaymentTransfer,
            $this->requestTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Transfer\TransferInterface $ratepayPaymentTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Mapper\PaymentMapper
     */
    public function getPaymentMapper(
        QuoteTransfer $quoteTransfer,
        $ratepayPaymentTransfer
    ) {
        return new PaymentMapper(
            $quoteTransfer,
            $ratepayPaymentTransfer,
            $this->requestTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer $ratepayPaymentTransfer
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
            $this->requestTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer $ratepayPaymentTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Mapper\InstallmentDetailMapper
     */
    public function getInstallmentDetailMapper(
        QuoteTransfer $quoteTransfer,
        $ratepayPaymentTransfer
    ) {
        return new InstallmentDetailMapper(
            $quoteTransfer,
            $ratepayPaymentTransfer,
            $this->requestTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer $ratepayPaymentTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Mapper\InstallmentPaymentMapper
     */
    public function getInstallmentPaymentMapper(
        QuoteTransfer $quoteTransfer,
        $ratepayPaymentTransfer
    ) {
        return new InstallmentPaymentMapper(
            $quoteTransfer,
            $ratepayPaymentTransfer,
            $this->requestTransfer
        );
    }

}

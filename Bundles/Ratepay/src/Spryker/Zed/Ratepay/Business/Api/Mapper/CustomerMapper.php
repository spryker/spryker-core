<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Mapper;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RatepayRequestCustomerTransfer;
use Spryker\Zed\Ratepay\Business\Api\Constants as ApiConstants;

class CustomerMapper extends BaseMapper
{

    const ALLOW_CREDIT_INQUIRY_YES = 'yes';
    const ALLOW_CREDIT_INQUIRY_NO = 'no';

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
        $ratepayPaymentTransfer,
        $requestTransfer
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
        $customerTransfer = $this->quoteTransfer->requireCustomer()->getCustomer();
        $billingAddress = $this->quoteTransfer->requireBillingAddress()->getBillingAddress();
        $shippingAddress = $this->quoteTransfer->requireBillingAddress()->getShippingAddress();

        $this->requestTransfer->setCustomer(new RatepayRequestCustomerTransfer())->getCustomer()
            ->setAllowCreditInquiry($this->prepareAllowCreditInquiry())
            ->setGender($this->ratepayPaymentTransfer->requireGender()->getGender())
            ->setDob($this->ratepayPaymentTransfer->requireDateOfBirth()->getDateOfBirth())
            ->setIpAddress($this->ratepayPaymentTransfer->requireIpAddress()->getIpAddress())
            ->setFirstName($billingAddress->getFirstName())
            ->setLastName($billingAddress->getLastName())
            ->setEmail($customerTransfer->requireEmail()->getEmail())
            ->setPhone($this->ratepayPaymentTransfer->requirePhone()->getPhone());

        $addressMapper = new AddressMapper(
            $billingAddress,
            ApiConstants::REQUEST_MODEL_ADDRESS_TYPE_BILLING,
            $this->requestTransfer
        );
        $addressMapper->map();

        $addressMapper = new AddressMapper(
            $shippingAddress,
            ApiConstants::REQUEST_MODEL_ADDRESS_TYPE_DELIVERY,
            $this->requestTransfer
        );
        $addressMapper->map();
    }

    /**
     * @return string
     */
    protected function prepareAllowCreditInquiry()
    {
        return ($this->ratepayPaymentTransfer->getCustomerAllowCreditInquiry() === false)
            ? self::ALLOW_CREDIT_INQUIRY_NO : self::ALLOW_CREDIT_INQUIRY_YES;
    }

}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Mapper;

use Generated\Shared\Transfer\RatepayPaymentRequestTransfer;
use Generated\Shared\Transfer\RatepayRequestCustomerTransfer;
use Spryker\Zed\Ratepay\Business\Api\Constants as ApiConstants;

class CustomerMapper extends BaseMapper
{
    public const ALLOW_CREDIT_INQUIRY_YES = 'yes';
    public const ALLOW_CREDIT_INQUIRY_NO = 'no';

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
        $requestTransfer
    ) {

        $this->ratepayPaymentRequestTransfer = $ratepayPaymentRequestTransfer;
        $this->requestTransfer = $requestTransfer;
    }

    /**
     * @return void
     */
    public function map()
    {
        $billingAddress = $this->ratepayPaymentRequestTransfer->requireBillingAddress()->getBillingAddress();
        $shippingAddress = $this->ratepayPaymentRequestTransfer->requireBillingAddress()->getShippingAddress();

        $this->requestTransfer->setCustomer(new RatepayRequestCustomerTransfer())->getCustomer()
            ->setAllowCreditInquiry($this->prepareAllowCreditInquiry())
            ->setGender($this->ratepayPaymentRequestTransfer->requireGender()->getGender())
            ->setDob($this->ratepayPaymentRequestTransfer->requireDateOfBirth()->getDateOfBirth())
            ->setIpAddress($this->ratepayPaymentRequestTransfer->requireIpAddress()->getIpAddress())
            ->setFirstName($billingAddress->getFirstName())
            ->setLastName($billingAddress->getLastName())
            ->setCompany($billingAddress->getCompany())
            ->setEmail($this->ratepayPaymentRequestTransfer->requireCustomerEmail()->getCustomerEmail())
            ->setPhone($this->ratepayPaymentRequestTransfer->requireCustomerPhone()->getCustomerPhone());

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
        return ($this->ratepayPaymentRequestTransfer->getCustomerAllowCreditInquiry() === false)
            ? self::ALLOW_CREDIT_INQUIRY_NO : self::ALLOW_CREDIT_INQUIRY_YES;
    }
}

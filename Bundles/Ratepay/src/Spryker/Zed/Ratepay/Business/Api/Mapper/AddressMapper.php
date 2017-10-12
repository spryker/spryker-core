<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Mapper;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\RatepayRequestAddressTransfer;
use Generated\Shared\Transfer\RatepayRequestTransfer;
use Spryker\Zed\Ratepay\Business\Api\Constants as ApiConstants;

class AddressMapper extends BaseMapper
{
    /**
     * @var \Generated\Shared\Transfer\AddressTransfer
     */
    protected $addressTransfer;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var \Generated\Shared\Transfer\RatepayRequestTransfer
     */
    protected $requestTransfer;

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param string $type
     * @param \Generated\Shared\Transfer\RatepayRequestTransfer $requestTransfer
     */
    public function __construct(AddressTransfer $addressTransfer, $type, RatepayRequestTransfer $requestTransfer)
    {
        $this->addressTransfer = $addressTransfer;
        $this->type = $type;
        $this->requestTransfer = $requestTransfer;
    }

    /**
     * @return void
     */
    public function map()
    {
        $this->prepareAddressTransfer()
            ->setCity($this->addressTransfer->requireCity()->getCity())
            ->setCountryCode($this->addressTransfer->requireIso2Code()->getIso2Code())
            ->setStreet($this->addressTransfer->requireAddress1()->getAddress1())
            ->setStreetAdditional($this->addressTransfer->getAddress3()) // This is optional.
            ->setStreetNumber($this->addressTransfer->requireAddress2()->getAddress2())
            ->setZipCode($this->addressTransfer->requireZipCode()->getZipCode());
    }

    /**
     * @return \Generated\Shared\Transfer\RatepayRequestAddressTransfer
     */
    protected function prepareAddressTransfer()
    {
        if ($this->type == ApiConstants::REQUEST_MODEL_ADDRESS_TYPE_DELIVERY) {
            $requestAddressTransfer = $this->requestTransfer->setShippingAddress(new RatepayRequestAddressTransfer())->getShippingAddress()
                ->setFirstName($this->addressTransfer->requireFirstName()->getFirstName())
                ->setLastName($this->addressTransfer->requireLastName()->getLastName());

            return $requestAddressTransfer;
        }

        $requestAddressTransfer = $this->requestTransfer->setBillingAddress(new RatepayRequestAddressTransfer())
            ->getBillingAddress();

        return $requestAddressTransfer;
    }
}

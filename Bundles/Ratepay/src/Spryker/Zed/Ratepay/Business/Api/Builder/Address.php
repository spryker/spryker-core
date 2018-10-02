<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Builder;

use Generated\Shared\Transfer\RatepayRequestTransfer;
use Spryker\Zed\Ratepay\Business\Api\Constants;

class Address extends AbstractBuilder implements BuilderInterface
{
    public const ROOT_TAG = 'address';

    /**
     * @var string
     */
    protected $addressType;

    /**
     * @param \Generated\Shared\Transfer\RatepayRequestTransfer $requestTransfer
     * @param string $addressType
     */
    public function __construct(RatepayRequestTransfer $requestTransfer, $addressType)
    {
        parent::__construct($requestTransfer);

        $this->addressType = $addressType;
    }

    /**
     * @return array
     */
    public function buildData()
    {
        $addressTransfer = ($this->addressType == Constants::REQUEST_MODEL_ADDRESS_TYPE_BILLING)
            ? $this->requestTransfer->getBillingAddress()
            : $this->requestTransfer->getShippingAddress();

        $result = ['@type' => $this->addressType];
        if ($addressTransfer->getFirstName() !== null) {
            $result['first-name'] = $addressTransfer->getFirstName();
        }
        if ($addressTransfer->getLastName() !== null) {
            $result['last-name'] = $addressTransfer->getLastName();
        }
        $result = array_merge(
            $result,
            [
                'street' => $addressTransfer->getStreet(),
                'street-additional' => $addressTransfer->getStreetAdditional(),
                'street-number' => $addressTransfer->getStreetNumber(),
                'zip-code' => $addressTransfer->getZipCode(),
                'city' => $addressTransfer->getCity(),
                'country-code' => $addressTransfer->getCountryCode(),
            ]
        );

        return $result;
    }

    /**
     * @return string
     */
    public function getRootTag()
    {
        return static::ROOT_TAG;
    }
}

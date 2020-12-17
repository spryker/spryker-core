<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataResponseMapper;

use Generated\Shared\Transfer\RestAddressTransfer;
use Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig;

class AddressCheckoutDataResponseMapper implements CheckoutDataResponseMapperInterface
{
    /**
     * @var \Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig
     */
    protected $checkoutRestApiConfig;

    /**
     * @param \Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig $checkoutRestApiConfig
     */
    public function __construct(CheckoutRestApiConfig $checkoutRestApiConfig)
    {
        $this->checkoutRestApiConfig = $checkoutRestApiConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $restCheckoutDataTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer
     */
    public function map(
        RestCheckoutDataTransfer $restCheckoutDataTransfer,
        RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
    ): RestCheckoutDataResponseAttributesTransfer {
        if (!$this->checkoutRestApiConfig->isAddressesMappedToAttributes()) {
            return $restCheckoutDataResponseAttributesTransfer;
        }

        return $this->mapAddresses($restCheckoutDataTransfer, $restCheckoutDataResponseAttributesTransfer);
    }

    /**
     * @deprecated Exists for BC reasons. Will be removed in the next major release.
     *
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $restCheckoutDataTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer
     */
    protected function mapAddresses(
        RestCheckoutDataTransfer $restCheckoutDataTransfer,
        RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
    ): RestCheckoutDataResponseAttributesTransfer {
        $addresses = $restCheckoutDataTransfer->getAddresses()->getAddresses();
        foreach ($addresses as $addressTransfer) {
            $restCheckoutDataResponseAttributesTransfer->addAddress(
                (new RestAddressTransfer())->fromArray(
                    $addressTransfer->toArray(),
                    true
                )->setId($addressTransfer->getUuid())
            );
        }

        return $restCheckoutDataResponseAttributesTransfer;
    }
}

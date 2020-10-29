<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi\Processor\Validator;

use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\ShipmentsRestApi\ShipmentsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class AddressSourceCheckoutDataValidator implements AddressSourceCheckoutDataValidatorInterface
{
    /**
     * @var \Spryker\Glue\ShipmentsRestApiExtension\Dependency\Plugin\AddressSourceProvidePluginInterface[]
     */
    protected $addressSourceProvidePlugins;

    /**
     * @param \Spryker\Glue\ShipmentsRestApiExtension\Dependency\Plugin\AddressSourceProvidePluginInterface[] $addressSourceProviderPlugins
     */
    public function __construct(array $addressSourceProviderPlugins)
    {
        $this->addressSourceProvidePlugins = $addressSourceProviderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    public function validateAttributes(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestErrorCollectionTransfer {
        $restErrorCollectionTransfer = new RestErrorCollectionTransfer();

        foreach ($restCheckoutRequestAttributesTransfer->getShipments() as $restShipmentsTransfer) {
            if (!$restShipmentsTransfer->getShippingAddress()) {
                continue;
            }

            foreach ($this->addressSourceProvidePlugins as $addressSourceProvidePlugin) {
                if ($addressSourceProvidePlugin->isAddressSourceProvided($restShipmentsTransfer->getShippingAddress())) {
                    continue 2;
                }
            }

            if (
                $restShipmentsTransfer->getShippingAddress()->getId()
                || $restShipmentsTransfer->getShippingAddress()->getAddress1()
                && $restShipmentsTransfer->getShippingAddress()->getAddress2()
                && $restShipmentsTransfer->getShippingAddress()->getCity()
                && $restShipmentsTransfer->getShippingAddress()->getZipCode()
                && $restShipmentsTransfer->getShippingAddress()->getIso2Code()
                && $restShipmentsTransfer->getShippingAddress()->getPhone()
                && $restShipmentsTransfer->getShippingAddress()->getSalutation()
                && $restShipmentsTransfer->getShippingAddress()->getFirstName()
                && $restShipmentsTransfer->getShippingAddress()->getLastName()
            ) {
                continue;
            }

            $restErrorCollectionTransfer->addRestError($this->createdRestErrorMessage());

            return $restErrorCollectionTransfer;
        }

        return $restErrorCollectionTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createdRestErrorMessage(): RestErrorMessageTransfer
    {
        return (new RestErrorMessageTransfer())
            ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->setCode(ShipmentsRestApiConfig::ERROR_RESPONSE_CODE_ADDRESS_NOT_VALID)
            ->setDetail(ShipmentsRestApiConfig::ERROR_RESPONSE_DETAIL_ADDRESS_NOT_VALID);
    }
}

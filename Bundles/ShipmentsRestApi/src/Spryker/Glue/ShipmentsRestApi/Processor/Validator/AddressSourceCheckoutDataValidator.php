<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi\Processor\Validator;

use Generated\Shared\Transfer\RestAddressTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\ShipmentsRestApi\ShipmentsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class AddressSourceCheckoutDataValidator implements AddressSourceCheckoutDataValidatorInterface
{
    /**
     * @var \Spryker\Glue\ShipmentsRestApiExtension\Dependency\Plugin\AddressSourceCheckerPluginInterface[]
     */
    protected $addressSourceCheckerPlugins;

    /**
     * @param \Spryker\Glue\ShipmentsRestApiExtension\Dependency\Plugin\AddressSourceCheckerPluginInterface[] $addressSourceCheckerPlugins
     */
    public function __construct(array $addressSourceCheckerPlugins)
    {
        $this->addressSourceCheckerPlugins = $addressSourceCheckerPlugins;
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

            if ($this->executeAddressSourceCheckerPlugins($restShipmentsTransfer->getShippingAddress())) {
                continue;
            }

            if ($this->validateAddressAttributes($restShipmentsTransfer->getShippingAddress())) {
                continue;
            }

            $restErrorCollectionTransfer->addRestError(
                (new RestErrorMessageTransfer())
                    ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                    ->setCode(ShipmentsRestApiConfig::ERROR_RESPONSE_CODE_ADDRESS_NOT_VALID)
                    ->setDetail(ShipmentsRestApiConfig::ERROR_RESPONSE_DETAIL_ADDRESS_NOT_VALID)
            );
        }

        return $restErrorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestAddressTransfer $restAddressTransfer
     *
     * @return bool
     */
    protected function executeAddressSourceCheckerPlugins(RestAddressTransfer $restAddressTransfer): bool
    {
        foreach ($this->addressSourceCheckerPlugins as $addressSourceProviderPlugin) {
            if ($addressSourceProviderPlugin->isAddressSourceProvided($restAddressTransfer)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\RestAddressTransfer $restAddressTransfer
     *
     * @return bool
     */
    protected function validateAddressAttributes(RestAddressTransfer $restAddressTransfer): bool
    {
        return $restAddressTransfer->getAddress1()
            && $restAddressTransfer->getAddress2()
            && $restAddressTransfer->getCity()
            && $restAddressTransfer->getZipCode()
            && $restAddressTransfer->getIso2Code()
            && $restAddressTransfer->getPhone()
            && $restAddressTransfer->getSalutation()
            && $restAddressTransfer->getFirstName()
            && $restAddressTransfer->getLastName();
    }
}

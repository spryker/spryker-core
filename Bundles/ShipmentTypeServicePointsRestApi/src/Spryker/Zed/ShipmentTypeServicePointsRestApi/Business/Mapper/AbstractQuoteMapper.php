<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeServicePointsRestApi\Business\Mapper;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\ShipmentTypeServicePointsRestApi\Business\Reader\CustomerReaderInterface;
use Spryker\Zed\ShipmentTypeServicePointsRestApi\Business\Reader\ShipmentTypeReaderInterface;
use Spryker\Zed\ShipmentTypeServicePointsRestApi\Dependency\Facade\ShipmentTypeServicePointsRestApiToShipmentFacadeInterface;

abstract class AbstractQuoteMapper implements QuoteMapperInterface
{
    /**
     * @var \Spryker\Zed\ShipmentTypeServicePointsRestApi\Business\Reader\ShipmentTypeReaderInterface
     */
    protected ShipmentTypeReaderInterface $shipmentTypeReader;

    /**
     * @var \Spryker\Zed\ShipmentTypeServicePointsRestApi\Business\Reader\CustomerReaderInterface
     */
    protected CustomerReaderInterface $customerReader;

    /**
     * @var \Spryker\Zed\ShipmentTypeServicePointsRestApi\Dependency\Facade\ShipmentTypeServicePointsRestApiToShipmentFacadeInterface
     */
    protected ShipmentTypeServicePointsRestApiToShipmentFacadeInterface $shipmentFacade;

    /**
     * @param \Spryker\Zed\ShipmentTypeServicePointsRestApi\Business\Reader\ShipmentTypeReaderInterface $shipmentTypeReader
     * @param \Spryker\Zed\ShipmentTypeServicePointsRestApi\Business\Reader\CustomerReaderInterface $customerReader
     * @param \Spryker\Zed\ShipmentTypeServicePointsRestApi\Dependency\Facade\ShipmentTypeServicePointsRestApiToShipmentFacadeInterface $shipmentFacade
     */
    public function __construct(
        ShipmentTypeReaderInterface $shipmentTypeReader,
        CustomerReaderInterface $customerReader,
        ShipmentTypeServicePointsRestApiToShipmentFacadeInterface $shipmentFacade
    ) {
        $this->shipmentTypeReader = $shipmentTypeReader;
        $this->customerReader = $customerReader;
        $this->shipmentFacade = $shipmentFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    abstract protected function isMappingRequired(QuoteTransfer $quoteTransfer): bool;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param array<int, \Generated\Shared\Transfer\ShipmentTypeTransfer> $shipmentTypeTransfersIndexedByIdShipmentMethod
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    abstract protected function expandShippingAddressWithCustomerData(
        QuoteTransfer $quoteTransfer,
        CustomerTransfer $customerTransfer,
        array $shipmentTypeTransfersIndexedByIdShipmentMethod
    ): QuoteTransfer;

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapCustomerAddressDataToShippingAddresses(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        if (!$this->isMappingRequired($quoteTransfer) || !$this->isCustomerDataProvided($restCheckoutRequestAttributesTransfer)) {
            return $quoteTransfer;
        }

        $customerTransfer = $this->customerReader->findCustomerTransferByCustomerReference(
            $restCheckoutRequestAttributesTransfer->getCustomerOrFail()->getCustomerReferenceOrFail(),
        );
        if (!$customerTransfer) {
            return $quoteTransfer;
        }

        $shipmentTypeTransfersIndexedByIdShipmentMethod = $this->shipmentTypeReader->getApplicableShipmentTypeTransfersIndexedByIdShipmentMethod(
            $quoteTransfer,
        );

        $quoteTransfer = $this->expandShippingAddressWithCustomerData(
            $quoteTransfer,
            $customerTransfer,
            $shipmentTypeTransfersIndexedByIdShipmentMethod,
        );

        return $this->shipmentFacade->expandQuoteWithShipmentGroups($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return bool
     */
    protected function isShipmentMethodDataSet(ShipmentTransfer $shipmentTransfer): bool
    {
        return $shipmentTransfer->getMethod() && $shipmentTransfer->getMethodOrFail()->getIdShipmentMethod();
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return bool
     */
    protected function isAddressCustomerDataSet(AddressTransfer $addressTransfer): bool
    {
        return $addressTransfer->getFirstName()
            && $addressTransfer->getLastName()
            && $addressTransfer->getSalutation();
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param array<int, \Generated\Shared\Transfer\ShipmentTypeTransfer> $applicableShipmentTypeTransfersIndexedByIdShipmentMethod
     *
     * @return bool
     */
    protected function isShipmentMethodApplicable(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        array $applicableShipmentTypeTransfersIndexedByIdShipmentMethod
    ): bool {
        return isset($applicableShipmentTypeTransfersIndexedByIdShipmentMethod[$shipmentMethodTransfer->getIdShipmentMethodOrFail()]);
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return bool
     */
    protected function isCustomerDataProvided(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): bool
    {
        return $restCheckoutRequestAttributesTransfer->getCustomer()
            && $restCheckoutRequestAttributesTransfer->getCustomerOrFail()->getCustomerReference();
    }
}

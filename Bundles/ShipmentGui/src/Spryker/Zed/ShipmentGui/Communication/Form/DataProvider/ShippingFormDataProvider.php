<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form\DataProvider;

use Spryker\Zed\ShipmentGui\Communication\Form\AddressForm;
use Spryker\Zed\ShipmentGui\Communication\Form\OrderItemCollectionForm;
use Spryker\Zed\ShipmentGui\Communication\Form\OrderItemForm;
use Spryker\Zed\ShipmentGui\Communication\Form\ShipmentForm;
use Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToCountryInterface;
use Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentBridge;
use Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentInterface;
use Spryker\Zed\ShipmentGui\Persistence\ShipmentGuiRepositoryInterface;

class ShippingFormDataProvider
{
    /**
     * @var \Spryker\Zed\ShipmentGui\Persistence\ShipmentGuiQueryContainerInterface
     */
    protected $shipmentGuiRepository;

    /**
     * @var \Spryker\Zed\ShipmentGui\Communication\Form\DataProvider\AddressFormDataProvider
     */
    protected $addressFormDataProvider;

    /**
     * @var \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentInterface
     */
    protected $shipmentFacade;

    /**
     * @param \Spryker\Zed\ShipmentGui\Persistence\ShipmentGuiQueryContainerInterface $shipmentGuiQueryContainer
     * @param AddressFormDataProvider $addressFormDataProvider
     * @param \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentInterface $shipmentFacade
     */
    public function __construct(
        ShipmentGuiRepositoryInterface $shipmentGuiRepository,
        AddressFormDataProvider $addressFormDataProvider,
        ShipmentGuiToShipmentInterface $shipmentFacade
    ) {
        $this->shipmentGuiRepository = $shipmentGuiRepository;
        $this->addressFormDataProvider = $addressFormDataProvider;
        $this->shipmentFacade = $shipmentFacade;
    }

    /**
     * @param int $idOrderAddress
     *
     * @return array
     */
    public function getData($idShipment, $orderTransfer)
    {
        $shipmentEntity = $this
            ->shipmentGuiRepository
            ->getShipmentById($idShipment);

        if ($shipmentEntity === null) {
            return [];
        }

        $shipmentAddressEntity = $shipmentEntity->getSpySalesOrderAddress();

        $formData = array_merge(
            [
//                ShipmentForm::FIELD_ORDER_ITEMS => '',
                ShipmentForm::FIELD_SHIPMENT_METHOD => $shipmentEntity->getCarrierName(),
                ShipmentForm::FIELD_SHIPMENT_DATE => $shipmentEntity->getRequestedDeliveryDate(),
            ],
            $this->addressFormDataProvider->getData(
                $shipmentAddressEntity->getIdSalesOrderAddress(),
                $shipmentAddressEntity
            )
        );

        return $formData;
    }

    /**
     * @return array
     */
    public function getOptions($orderTransfer)
    {
        $options =  array_merge(
            [
                OrderItemCollectionForm::CHOICES_ORDER_ITEM => $this->createOrderItemOptionList($orderTransfer),
                ShipmentForm::OPTION_SHIPMENT_METHOD => [
                    'Shipment example 1' => 1,
                    'Shipment example 2' => 2,
                ],
            ],
            $this->addressFormDataProvider->getOptions()
        );

        return $options;
    }

    protected function createOrderItemOptionList($orderTransfer)
    {


        return $orderTransfer->getItems();
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodsTransfer $shipmentMethodCollection
     *
     * @return Spryker\Zed\Sales\Communication\Form\DataProvider\AddressFormDataProvider
     */
    protected function createShipmentMethodOptionList(ShipmentMethodsTransfer $shipmentMethodCollection)
    {
        $data = [];

        foreach ($shipmentMethodCollection->getMethods() as $shipmentMethod) {
            $data[$shipmentMethod->getIdShipmentMethod()] = $shipmentMethod->getCarrierName();
        }

        return $data;
    }
}

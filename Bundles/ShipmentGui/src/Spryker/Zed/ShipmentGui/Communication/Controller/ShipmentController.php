<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Controller;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ShipmentFormTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\ShipmentGui\Communication\Form\ShipmentForm;
use Spryker\Zed\ShipmentGui\ShipmentGuiConfig;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ShipmentGui\Communication\ShipmentGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ShipmentGui\Persistence\ShipmentGuiQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ShipmentGui\Business\ShipmentGuiFacadeInterface getFacade()
 */
class ShipmentController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(Request $request)
    {
        $idSalesOrder = $this->castId($request->query->get(ShipmentGuiConfig::PARAM_ID_SALES_ORDER));
        $orderTransfer = $this
            ->getFactory()
            ->getSalesFacade()
            ->findOrderByIdSalesOrder($idSalesOrder);

        if ($orderTransfer === null) {
            $this->addErrorMessage(sprintf(
                'Sales order #%d not found.',
                $idSalesOrder
            ));

            return $this->redirectResponse(Url::generate('/sales')->build());
        }

        $idShipment = $this->castId($request->query->get(ShipmentGuiConfig::PARAM_ID_SHIPMENT));

        $shipmentTransfer = $this->getFactory()
            ->getShipmentFacade()
            ->findShipmentById($idShipment);

        if ($shipmentTransfer === null) {
            $this->addErrorMessage(sprintf(
                'Shipment with #%d not found.',
                $idShipment
            ));

            return $this
                ->redirectResponse(Url::generate('/sales')
                    ->build());
        }

        /**
         * @todo: Replace with real logic.
         */
        $shipmentFormTransfer = (function () use ($orderTransfer, $shipmentTransfer) {
            $shipmentFormTransfer = (new ShipmentFormTransfer())
                ->fromArray($shipmentTransfer->toArray(), true);
            $shipmentFormTransfer->setOrderItems($orderTransfer->getItems());

            return $shipmentFormTransfer;
        })();

        $addressFormDataprovider = $this->getFactory()->createAddressFormDataProvider();
        $shipmentMethodCollection = $this->getFactory()
            ->getShipmentFacade()
            ->getMethods();
        /**
         * @todo: Replace with real logic.
         */
        $shipmentAddressCollection = (function () {
            $collection = [
                1 => 'Volkswagen Group, Hellmuth-Hirth-Strasse 1',
                0 => 'Create New',
            ];

            return $collection;
        })();
        $shipmentFormOptions = (function () use ($shipmentTransfer, $orderTransfer, $addressFormDataprovider, $shipmentMethodCollection, $shipmentAddressCollection) {
            $assignedOrderItems = [];
            foreach ($orderTransfer->getItems() as $orderItem) {
                if ($orderItem->getId() == 27) {
                    $assignedOrderItems[] = $orderItem->getId();
                }
            }

            $shipmentMethodChoices = [];
            foreach ($shipmentMethodCollection as $shipmentMethod) {
                $shipmentMethodChoices[$shipmentMethod->getName()] = $shipmentMethod->getName();
            }

            $formOptions = array_merge(
                [
                    ShipmentForm::CHOICES_SHIPMENT_METHOD => $shipmentMethodChoices,
                    ShipmentForm::CHOICES_SHIPMENT_ADDRESS => $shipmentAddressCollection,
                    ShipmentForm::SELECTED_ORDER_ITEMS => $assignedOrderItems,
                ],
                $addressFormDataprovider->getOptions()
            );

            return $formOptions;
        })();

        $shipmentForm = $this->getFactory()
            ->getShipmentForm(
                $shipmentFormTransfer,
                $shipmentFormOptions
            )
            ->handleRequest($request);

        if ($shipmentForm->isSubmitted() && $shipmentForm->isValid()) {
            /**
             * @todo: Update or Create shipment address
             */

//            $this->addSuccessMessage('Address successfully updated.');
//
//            return $this->redirectResponse(
//                Url::generate(
//                    '/sales/detail',
//                    [
//                        ShipmentGuiConfig::PARAM_ID_SALES_ORDER => $idSalesOrder,
//                    ]
//                )->build()
//            );
        }

        return $this->viewResponse([
            'idSalesOrder' => $idSalesOrder,
            'shipmentForm' => $shipmentForm->createView(),
        ]);
    }
}

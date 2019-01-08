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
        /**
         * @todo: Replace with real logic.
         */
        /** @var ShipmentTransfer $shipmentTransfer */
        $shipmentTransfer = (function () use ($idShipment): ShipmentTransfer {
            $spySalesShipment = \Orm\Zed\Sales\Persistence\SpySalesShipmentQuery::create()
                ->findOneByIdSalesShipment($idShipment);

            $shipmentTransfer = new ShipmentTransfer();
            $shipmentTransfer->setMethod((new ShipmentMethodTransfer())->setName($spySalesShipment->getName()));
            $shipmentTransfer->setShippingAddress(
                (new AddressTransfer())->fromArray(
                    $spySalesShipment->getOrder()->getShippingAddress()->toArray(),
                    true
                )
            );

            return $shipmentTransfer;
        })();

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
            $shipmentFormTransfer = new ShipmentFormTransfer();
            $shipmentFormTransfer->setMethod($shipmentTransfer->getMethod());
            $shipmentFormTransfer->setRequestedDeliveryDate($shipmentTransfer->getRequestedDeliveryDate());
            $shipmentFormTransfer->setShippingAddress($shipmentTransfer->getShippingAddress());
            $shipmentFormTransfer->setOrderItems($orderTransfer->getItems());

            return $shipmentFormTransfer;
        })();
        /**
         * @todo: Replace with real logic.
         */
        $addressFormDataprovider = $this->getFactory()->createAddressFormDataProvider();
        $shipmentFormOptions = (function () use ($orderTransfer, $addressFormDataprovider) {
            $formOptions = array_merge(
                [
                    ShipmentForm::CHOICES_SHIPMENT_METHOD => [
                        'Meth 1' => 1,
                    ]
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
             * Update shipment
             */
//            $this->getFacade()
//                ->updateOrderAddress($addressTransfer, $idShipment);
            /**
             * Add address for shipment
             */

            $this->addSuccessMessage('Address successfully updated.');

            return $this->redirectResponse(
                Url::generate(
                    '/sales/detail',
                    [
                        SalesConfig::PARAM_ID_SALES_ORDER => $idSalesOrder,
                    ]
                )->build()
            );
        }

        return $this->viewResponse([
            'idSalesOrder' => $idSalesOrder,
            'shipmentForm' => $shipmentForm->createView(),
        ]);
    }
}

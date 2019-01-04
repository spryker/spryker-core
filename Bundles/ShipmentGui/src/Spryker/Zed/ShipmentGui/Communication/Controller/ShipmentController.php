<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Controller;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ShipmentFormTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\Sales\SalesConfig;
use Spryker\Zed\ShipmentGui\Communication\Form\ShipmentForm;
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
        $idSalesOrder = $this->castId($request->query->get(SalesConfig::PARAM_ID_SALES_ORDER));
        $idShipment = $this->castId($request->query->get('id-shipment'));

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

        // get shipment

        //$shipmentAddressEntity = $shipmentEntity->getSpySalesOrderAddress();

        $shipmentFormTransfer = new ShipmentFormTransfer();
        $shipmentFormTransfer->setOrderItems($orderTransfer->getItems());

        ////////////////

        $shipmentForm = $this->getFactory()
            ->getShipmentForm(
                $shipmentFormTransfer,
                array_merge(
                    $this->getFactory()->createAddressFormDataProvider()->getOptions(),
                    [
                        ShipmentForm::CHOICES_SHIPMENT_METHOD => [
                            'Meth 1' => 1,
                        ],
                    ]
                )
            )
            ->handleRequest($request);

        if ($shipmentForm->isSubmitted() && $shipmentForm->isValid()) {
            $addressTransfer = (new AddressTransfer())->fromArray($shipmentForm->getData(), true);
            $addressTransfer->setIdSalesOrderAddress($idShipment);
            $this->getFacade()
                ->updateOrderAddress($addressTransfer, $idShipment);

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
/*dump($shipmentForm->createView());
        exit();*/
        return $this->viewResponse([
            'idSalesOrder' => $idSalesOrder,
            'shipmentForm' => $shipmentForm->createView(),
            'orderItemTransferCollection' => $orderTransfer->getItems()->getArrayCopy(),
            'eventsGroupedByItem' => [],
            'order' => $orderTransfer,
        ]);
    }
}

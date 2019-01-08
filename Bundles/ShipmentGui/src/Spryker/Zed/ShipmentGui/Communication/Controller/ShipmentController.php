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
        $idShipment = $this->castId($request->query->get('id-shipment'));
        $orderTransfer = $this
            ->getFactory()
            ->getShipmentFacade()
            ->findBy($idSalesOrder);

        if ($orderTransfer === null) {
            $this->addErrorMessage(sprintf(
                'Sales order #%d not found.',
                $idSalesOrder
            ));

            return $this
                ->redirectResponse(Url::generate('/sales')
                    ->build());
        }

        $shipmentFormDataProvider = $this->getFactory()->createShipmentFormDataProvider();

        $shipmentFormTransfer = new ShipmentFormTransfer();
        $shipmentFormTransfer->setOrderItems($orderTransfer->getItems());

        $shipmentForm = $this->getFactory()
            ->getShipmentForm(
                $shipmentFormDataProvider->getTransferByIdShipment($idShipment),
                $shipmentFormDataProvider->getOptions()
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

//            $this->addSuccessMessage('Address successfully updated.');
//
//            return $this->redirectResponse(
//                Url::generate(
//                    '/sales/detail',
//                    [
//                        SalesConfig::PARAM_ID_SALES_ORDER => $idSalesOrder,
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

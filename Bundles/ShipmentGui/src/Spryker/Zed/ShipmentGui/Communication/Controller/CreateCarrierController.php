<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Controller;

use Generated\Shared\Transfer\ShipmentCarrierTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ShipmentGui\Communication\ShipmentGuiCommunicationFactory getFactory()
 */
class CreateCarrierController extends AbstractController
{
    protected const MESSAGE_CARRIER_CREATE_SUCCESS = 'Carrier was created successfully.';
    protected const URL_REDIRECT_SHIPMENT = '/shipment';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $factory = $this->getFactory();
        $carrierFormDataProvider = $factory->createShipmentCarrierFormDataProvider();

        $form = $factory->createShipmentCarrierFormType($carrierFormDataProvider->getData(), $carrierFormDataProvider->getOptions())
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $shipmentCarrierTransfer = $this->mapRequestDataToShipmentCarrierTransfer($form->getData(), new ShipmentCarrierTransfer());

            $factory->getShipmentFacade()->createCarrier($shipmentCarrierTransfer);

            $this->addSuccessMessage(static::MESSAGE_CARRIER_CREATE_SUCCESS);

            return $this->redirectResponse(static::URL_REDIRECT_SHIPMENT);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param array $requestData
     * @param \Generated\Shared\Transfer\ShipmentCarrierTransfer $shipmentCarrierTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentCarrierTransfer
     */
    protected function mapRequestDataToShipmentCarrierTransfer(array $requestData, ShipmentCarrierTransfer $shipmentCarrierTransfer): ShipmentCarrierTransfer
    {
        return $shipmentCarrierTransfer->fromArray($requestData, true);
    }
}

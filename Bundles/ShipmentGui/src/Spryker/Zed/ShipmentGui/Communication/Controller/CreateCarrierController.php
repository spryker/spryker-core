<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Controller;

use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ShipmentGui\Communication\ShipmentGuiCommunicationFactory getFactory()
 */
class CreateCarrierController extends AbstractController
{
    protected const MESSAGE_CARRIER_CREATE_SUCCESS = 'Carrier was created successfully.';
    protected const ROUTE_SHIPMENT = '/shipment';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $factory = $this->getFactory();
        $shipmentCarrierForm = $factory->createShipmentCarrierFormType()->handleRequest($request);

        if ($shipmentCarrierForm->isSubmitted() && $shipmentCarrierForm->isValid()) {
            $factory->getShipmentFacade()->createCarrier($shipmentCarrierForm->getData());
            $this->addSuccessMessage(static::MESSAGE_CARRIER_CREATE_SUCCESS);
            $redirectUrl = Url::generate(static::ROUTE_SHIPMENT)->build();

            return $this->redirectResponse($redirectUrl);
        }

        return $this->viewResponse([
            'form' => $shipmentCarrierForm->createView(),
        ]);
    }
}

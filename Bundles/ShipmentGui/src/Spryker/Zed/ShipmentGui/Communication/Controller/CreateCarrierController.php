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
    public const REQUEST_ID_CARRIER = 'idShipmentCarrier';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $factory = $this->getFactory();
        $carrierFormDataProvider = $factory->createShipmentCarrierFormDataProvider();

        $idShipmentCarrier = $request->query->getInt(static::REQUEST_ID_CARRIER);
        $companyRoleTransfer = (new ShipmentCarrierTransfer())->setIdShipmentCarrier($idShipmentCarrier);

        $form = $factory->createShipmentCarrierFormType($carrierFormDataProvider->getData($companyRoleTransfer), $carrierFormDataProvider->getOptions())
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $factory->getShipmentFacade()->createCarrier($form->getData());

            $this->addSuccessMessage(static::MESSAGE_CARRIER_CREATE_SUCCESS);

            return $this->redirectResponse(static::URL_REDIRECT_SHIPMENT);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }
}

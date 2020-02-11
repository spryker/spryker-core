<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ShipmentGui\Communication\ShipmentGuiCommunicationFactory getFactory()
 */
class DeleteShipmentMethodController extends AbstractController
{
    protected const PARAM_ID_SHIPMENT_METHOD = 'id-shipment-method';

    protected const REDIRECT_URL = '/shipment-gui/shipment-method';

    protected const MESSAGE_DELIVERY_METHOD_NOT_FOUND = 'Delivery method not found.';
    protected const MESSAGE_SUCCESS = 'Delivery method was deleted successfully.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idShipmentMethod = $this->castId($request->query->get(static::PARAM_ID_SHIPMENT_METHOD));
        $shipmentMethodTransfer = $this->getFactory()->getShipmentFacade()->findMethodById($idShipmentMethod);
        if ($shipmentMethodTransfer === null) {
            $this->addErrorMessage(static::MESSAGE_DELIVERY_METHOD_NOT_FOUND);

            return $this->redirectResponse(static::REDIRECT_URL);
        }

        $deleteShipmentMethodForm = $this->getFactory()->createShipmentMethodDeleteForm();
        $deleteShipmentMethodForm->handleRequest($request);
        if ($deleteShipmentMethodForm->isSubmitted() && $deleteShipmentMethodForm->isValid()) {
            return $this->handleSubmitForm($idShipmentMethod);
        }

        return $this->viewResponse([
            'shipmentMethod' => $shipmentMethodTransfer,
            'deleteShipmentMethodForm' => $deleteShipmentMethodForm->createView(),
        ]);
    }

    /**
     * @param int $idShipmentMethod
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function handleSubmitForm(int $idShipmentMethod): RedirectResponse
    {
        $this->getFactory()->getShipmentFacade()->deleteMethod($idShipmentMethod);
        $this->addSuccessMessage(static::MESSAGE_SUCCESS);

        return $this->redirectResponse(static::REDIRECT_URL);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ShipmentGui\Communication\ShipmentGuiCommunicationFactory getFactory()
 */
class ViewShipmentMethodController extends AbstractController
{
    protected const REQUEST_ID_SHIPMENT_METHOD = 'id-shipment-method';
    protected const REDIRECT_URL = '/shipment-gui/shipment-method';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idShipmentMethod = $request->query->getInt(static::REQUEST_ID_SHIPMENT_METHOD);

        $shipmentMethodTransfer = $this->getFactory()
            ->getShipmentFacade()
            ->findMethodById($idShipmentMethod);

        if ($shipmentMethodTransfer === null) {
            return $this->redirectResponse(static::REDIRECT_URL);
        }

        $dataProvider = $this->getFactory()->createViewShipmentMethodFormDataProvider();
        $form = $this->getFactory()->createViewShipmentMethodForm(
            $dataProvider->getData($shipmentMethodTransfer),
            $dataProvider->getOptions()
        );

        return $this->viewResponse([
            'form' => $form->createView(),
            'shipmentMethod' => $shipmentMethodTransfer,
        ]);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ShipmentGui\Communication\ShipmentGuiCommunicationFactory getFactory()
 */
class UpdateShipmentMethodController extends AbstractController
{
    /**
     * @uses \Spryker\Zed\StockGui\Communication\Controller\WarehouseController::listAction()
     */
    protected const REDIRECT_URL = '/shipment-gui/shipment-method/index';
    protected const MESSAGE_SUCCESS = 'Shipment method has been successfully updated';
    protected const MESSAGE_SHIPMENT_METHOD_NOT_FOUND = 'Shipment method not found';
    protected const PARAMETER_ID_SHIPMENT_METHOD = 'id-shipment-method';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idShipmentMethod = $this->castId(
            $request->query->getInt(static::PARAMETER_ID_SHIPMENT_METHOD)
        );

        $shipmentMethodTransfer = $this->getFactory()
            ->getShipmentFacade()
            ->findMethodById($idShipmentMethod);

        if ($shipmentMethodTransfer === null) {
            $this->addErrorMessage(static::MESSAGE_SHIPMENT_METHOD_NOT_FOUND);

            return $this->redirectResponse(static::REDIRECT_URL);
        }

        $shipmentMethodTabs = $this->getFactory()->createShipmentMethodTabs();
        $dataProvider = $this->getFactory()->createShipmentMethodFormDataProvider();
        $shipmentMethodForm = $this->getFactory()
            ->createShipmentMethodForm(
                $dataProvider->getData($shipmentMethodTransfer),
                $dataProvider->getOptions()
            );
        $shipmentMethodForm->handleRequest($request);

        if ($shipmentMethodForm->isSubmitted() && $shipmentMethodForm->isValid()) {
            return $this->handleShipmentMethodForm($shipmentMethodForm);
        }

        return $this->viewResponse([
            'shipmentMethodForm' => $shipmentMethodForm->createView(),
            'shipmentMethodTabs' => $shipmentMethodTabs->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $shipmentMethodForm
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function handleShipmentMethodForm(FormInterface $shipmentMethodForm): RedirectResponse
    {
        $this->getFactory()
            ->getShipmentFacade()
            ->updateMethod($shipmentMethodForm->getData());

        $this->addSuccessMessage(static::MESSAGE_SUCCESS);

        return $this->redirectResponse(static::REDIRECT_URL);
    }
}

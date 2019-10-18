<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Controller;

use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ShipmentGui\Communication\ShipmentGuiCommunicationFactory getFactory()
 */
class CreateShipmentMethodController extends AbstractController
{
    /**
     * @uses \Spryker\Zed\ShipmentGui\Communication\Controller\ShipmentMethodController::indexAction()
     */
    protected const REDIRECT_URL = '/shipment-gui/shipment-method/index';
    protected const MESSAGE_SUCCESS = 'Shipment method has been successfully saved';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $shipmentMethodTabs = $this->getFactory()->createShipmentMethodTabs();
        $dataProvider = $this->getFactory()->createShipmentMethodFormDataProvider();
        $shipmentMethodForm = $this->getFactory()->createShipmentMethodForm(
            $dataProvider->getData(new ShipmentMethodTransfer()),
            $dataProvider->getOptions()
        );
        $shipmentMethodForm->handleRequest($request);
        
        if ($shipmentMethodForm->isSubmitted() && $shipmentMethodForm->isValid()) {
            return $this->handleShipmentMethodForm($shipmentMethodForm);
        }

        return $this->viewResponse([
            'shipmentMethodTabs' => $shipmentMethodTabs->createView(),
            'shipmentMethodForm' => $shipmentMethodForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function handleShipmentMethodForm(FormInterface $form): RedirectResponse
    {
        /** @var \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer */
        $shipmentMethodTransfer = $form->getData();
        $this->getFactory()->getShipmentFacade()->createMethod($shipmentMethodTransfer);
        $this->addSuccessMessage(static::MESSAGE_SUCCESS);

        return $this->redirectResponse(static::REDIRECT_URL);
    }
}

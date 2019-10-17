<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Controller;

use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ShipmentGui\Communication\ShipmentGuiCommunicationFactory getFactory()
 */
class CreateShipmentMethodController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $shipmentMethodTabs = $this->getFactory()->createShipmentMethodTabs();
        $dataProvider = $this->getFactory()->createShipmentMethodFormDataProvider();
        $shipmentMethodForm = $this->getFactory()->createShipmentMethodForm(
            $dataProvider->getData(new ShipmentMethodTransfer()),
            $dataProvider->getOptions()
        );

        return $this->viewResponse([
            'shipmentMethodTabs' => $shipmentMethodTabs->createView(),
            'shipmentMethodForm' => $shipmentMethodForm->createView(),
        ]);
    }
}

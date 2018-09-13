<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\SettingsForm;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\SalesOrderThresholdGui\Communication\SalesOrderThresholdGuiCommunicationFactory getFactory()
 */
class SettingsController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $dataProvider = $this->getFactory()->createSettingsFromDataProvider();
        $form = $this->getFactory()->getSettingsForm($dataProvider);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $idTaxSet = (int)$form->getData()[SettingsForm::FIELD_TAX_SET];
            $this->getFactory()
                ->getSalesOrderThresholdFacade()
                ->saveSalesOrderThresholdTaxSet($idTaxSet);

            $this->addSuccessMessage('Minimum Order Value settings saved.');
        }

        return [
            'form' => $form->createView(),
        ];
    }
}

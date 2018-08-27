<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MinimumOrderValueGui\Communication\MinimumOrderValueGuiCommunicationFactory getFactory()
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

        return [
            'form' => $form->createView(),
        ];
    }
}

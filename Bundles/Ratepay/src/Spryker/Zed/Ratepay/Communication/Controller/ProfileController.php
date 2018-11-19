<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\Ratepay\Business\RatepayFacadeInterface getFacade()
 * @method \Spryker\Zed\Ratepay\Communication\RatepayCommunicationFactory getFactory()
 * @method \Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface getQueryContainer()
 */
class ProfileController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction()
    {
        $profileResponse = $this->getFacade()->requestProfile();

        return $this->viewResponse([
            'masterData' => $profileResponse->getMasterData(),
            'installmentConfigurationResult' => $profileResponse->getInstallmentConfigurationResult(),
        ]);
    }
}

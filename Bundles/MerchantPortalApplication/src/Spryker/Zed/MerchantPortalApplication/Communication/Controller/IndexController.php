<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantPortalApplication\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @method \Spryker\Zed\MerchantPortalApplication\Business\MerchantPortalApplicationFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantPortalApplication\Communication\MerchantPortalApplicationCommunicationFactory getFactory()
 */
class IndexController extends AbstractController
{
    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(): RedirectResponse
    {
        $homePageUrl = $this->getFactory()
            ->getConfig()
            ->getHomePageUrl();

        return $this->redirectResponse($homePageUrl);
    }
}

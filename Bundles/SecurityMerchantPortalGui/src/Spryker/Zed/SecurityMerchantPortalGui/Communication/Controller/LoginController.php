<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityMerchantPortalGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\SecurityMerchantPortalGui\Communication\SecurityMerchantPortalGuiCommunicationFactory getFactory()
 */
class LoginController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        if ($this->getFactory()->getSecurityFacade()->isUserLoggedIn()) {
            return $this->redirectResponse(
                $this->getFactory()->getConfig()->getDefaultTargetPath()
            );
        }

        return $this->viewResponse([
            'form' => $this
                ->getFactory()
                ->createLoginForm()
                ->handleRequest($request)
                ->createView(),
        ]);
    }
}

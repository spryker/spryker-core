<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\AgentSecurityMerchantPortalGuiCommunicationFactory getFactory()
 */
class LoginController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    public function indexAction(Request $request)
    {
        if ($this->getFactory()->getSecurityFacade()->isUserLoggedIn()) {
            return $this->redirectResponse(
                $this->getFactory()->getConfig()->getUrlDefaultTarget(),
            );
        }

        return $this->viewResponse([
            'form' => $this
                ->getFactory()
                ->createAgentMerchantLoginForm()
                ->handleRequest($request)
                ->createView(),
        ]);
    }
}

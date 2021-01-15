<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\SecurityGui\Communication\SecurityGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\SecurityGui\Business\SecurityGuiFacadeInterface getFacade()
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
            return $this->redirectResponse($this->getFactory()->getConfig()->getUrlHome());
        }

        $loginForm = $this->getFactory()
            ->createLoginForm()
            ->handleRequest($request)
            ->createView();

        $oauthAuthenticationLinkTransfers = $this->executeAuthenticationLinkPlugins();

        return $this->viewResponse([
            'form' => $loginForm,
            'authenticationLinkCollection' => $oauthAuthenticationLinkTransfers,
        ]);
    }

    /**
     * @return \Generated\Shared\Transfer\OauthAuthenticationLinkTransfer[]
     */
    protected function executeAuthenticationLinkPlugins(): array
    {
        $oauthAuthenticationLinkTransfers = [];

        foreach ($this->getFactory()->getAuthenticationLinkPlugins() as $authenticationLinkPlugin) {
            $oauthAuthenticationLinkTransfers[] = $authenticationLinkPlugin->getAuthenticationLink();
        }

        return $oauthAuthenticationLinkTransfers;
    }
}

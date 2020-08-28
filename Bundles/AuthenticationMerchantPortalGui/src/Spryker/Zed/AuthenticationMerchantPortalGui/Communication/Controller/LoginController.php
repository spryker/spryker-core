<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AuthenticationMerchantPortalGui\Communication\Controller;

use Spryker\Shared\Auth\AuthConstants;
use Spryker\Zed\AuthenticationMerchantPortalGui\Communication\Form\MerchantLoginForm;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\AuthenticationMerchantPortalGui\Communication\AuthenticationMerchantPortalGuiCommunicationFactory getFactory()
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
        $authFacade = $this->getFactory()->getAuthFacade();
        $token = $request->headers->get(AuthConstants::AUTH_TOKEN, null);

        if ($authFacade->hasCurrentUser()) {
            $token = $authFacade->getCurrentUserToken();
        }

        if ($authFacade->isAuthenticated($token)) {
            return $this->redirectResponse($this->getFactory()->getConfig()->getDefaultTargetPath());
        }

        $form = $this->getFactory()->createLoginForm()->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $isLoggedIn = $authFacade->login(
                $formData[MerchantLoginForm::FIELD_USERNAME],
                $formData[MerchantLoginForm::FIELD_PASSWORD]
            );

            if ($isLoggedIn) {
                return $this->redirectResponse($this->getFactory()->getConfig()->getDefaultTargetPath());
            }

            $this->addErrorMessage('Authentication failed!');
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }
}

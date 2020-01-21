<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUserAuthGuiPage\Communication\Controller;

use Spryker\Shared\Auth\AuthConstants;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\MerchantUserAuthGuiPage\Communication\Form\LoginForm;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantUserAuthGuiPage\Communication\MerchantUserAuthGuiPageCommunicationFactory getFactory()
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

        $form = $this
            ->getFactory()
            ->createLoginForm()
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $isLogged = $authFacade->login(
                $formData[LoginForm::FIELD_USERNAME],
                $formData[LoginForm::FIELD_PASSWORD]
            );

            if ($isLogged) {
                return $this->redirectResponse($this->getFactory()->getConfig()->getDefaultUrlRedirect());
            }

            $this->addErrorMessage('Authentication failed!');
        } else {
            $token = null;
            if ($authFacade->hasCurrentUser()) {
                $token = $authFacade->getCurrentUserToken();
            }

            if ($request->headers->get(AuthConstants::AUTH_TOKEN)) {
                $token = $request->headers->get(AuthConstants::AUTH_TOKEN);
            }

            if ($authFacade->isAuthenticated($token)) {
                return $this->redirectResponse($this->getFactory()->getConfig()->getDefaultUrlRedirect());
            }
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }
}

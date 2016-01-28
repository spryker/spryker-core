<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Auth\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Auth\Communication\Form\LoginForm;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Auth\Communication\AuthCommunicationFactory getFactory()
 * @method \Spryker\Zed\Auth\Business\AuthFacade getFacade()
 */
class LoginController extends AbstractController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $form = $this
            ->getFactory()
            ->createLoginForm()
            ->handleRequest($request);

        if ($form->isValid()) {
            $formData = $form->getData();

            $isLogged = $this->getFacade()->login(
                $formData[LoginForm::FIELD_USERNAME],
                $formData[LoginForm::FIELD_PASSWORD]
            );

            if ($isLogged) {
                return $this->redirectResponse('/');
            } else {
                $this->addErrorMessage('Authentication failed!');
            }
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }

}

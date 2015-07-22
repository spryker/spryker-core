<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Auth\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Shared\Auth\Messages\Messages;
use SprykerFeature\Zed\Auth\Communication\AuthDependencyContainer;
use SprykerFeature\Zed\Auth\Communication\Form\LoginForm;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method AuthDependencyContainer getDependencyContainer()
 */
class LoginController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $form = $this->getDependencyContainer()
            ->createLoginForm($request)
            ->init()
        ;
        $form->handleRequest();

        $error = null;

        if ($request->isMethod(Request::METHOD_POST) && $form->isValid()) {
            $formData = $form->getData();

            $isLogged = $this->getDependencyContainer()
                ->locateAuthFacade()
                ->login($formData[LoginForm::USERNAME], $formData[LoginForm::PASSWORD])
            ;

            if (true === $isLogged) {
                return $this->redirectResponse('/');
            } else {
                $error = 'Authentication failed';
            }
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'error' => $error,
        ]);
    }

}

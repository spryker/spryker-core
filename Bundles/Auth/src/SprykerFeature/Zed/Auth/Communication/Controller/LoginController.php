<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Auth\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Shared\Auth\Messages\Messages;
use SprykerFeature\Zed\Auth\Communication\AuthDependencyContainer;
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
        $form = $this->getDependencyContainer()->createLoginForm($request);

        $form->init();

        return $this->viewResponse([
            'form' => json_encode($form->toArray()),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function checkAction(Request $request)
    {
        $statusCode = 401;
        $headers = [];

        $facade = $this->getDependencyContainer()->locateAuthFacade();

        $form = $this->getDependencyContainer()->createLoginForm($request);
        $form->init();

        $formData = $form->getRequestData();

        if (false === $form->isValid()) {
            return $this->jsonResponse($form->toArray(), $statusCode, $headers);
        }

        //Business logic validation
        $isLogged = $facade->login($formData['username'], $formData['password']);
        $response = $form->toArray();

        if (true === $isLogged) {
            $statusCode = 200;
            $headers['Spy-Location'] = '/';
        } else {
            $response['fields'][0]['messages'][] = Messages::ERROR_LOGIN_NOT_FOUND;
            $statusCode = 401;
        }

        return $this->jsonResponse(['content' => $response], $statusCode, $headers);
    }

}

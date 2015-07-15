<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Auth\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Auth\Communication\AuthDependencyContainer;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @method AuthDependencyContainer getDependencyContainer()
 */
class LogoutController extends AbstractController
{

    /**
     * @return RedirectResponse
     */
    public function indexAction()
    {
        $facade = $this->getDependencyContainer()->locateAuthFacade();

        $facade->logout();

        return $this->redirectResponse('/', 302);
    }

}

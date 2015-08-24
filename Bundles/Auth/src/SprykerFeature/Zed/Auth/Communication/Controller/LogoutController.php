<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Auth\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Auth\Communication\AuthDependencyContainer;
use Symfony\Component\HttpFoundation\RedirectResponse;
use SprykerFeature\Zed\Auth\Business\AuthFacade;

/**
 * @method AuthDependencyContainer getDependencyContainer()
 * @method AuthFacade getFacade()
 */
class LogoutController extends AbstractController
{
    /**
     * @return RedirectResponse
     */
    public function indexAction()
    {
        $this->getFacade()->logout();

        return $this->redirectResponse('/', 302);
    }

}

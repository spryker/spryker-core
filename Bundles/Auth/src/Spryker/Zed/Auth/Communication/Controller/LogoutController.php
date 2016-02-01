<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Auth\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Auth\Communication\AuthCommunicationFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Spryker\Zed\Auth\Business\AuthFacade;

/**
 * @method AuthCommunicationFactory getFactory()
 * @method AuthFacade getFacade()
 */
class LogoutController extends AbstractController
{

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction()
    {
        $this->getFacade()->logout();

        return $this->redirectResponse('/', 302);
    }

}

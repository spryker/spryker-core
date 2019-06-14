<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Auth\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\Auth\Communication\AuthCommunicationFactory getFactory()
 * @method \Spryker\Zed\Auth\Business\AuthFacadeInterface getFacade()
 * @method \Spryker\Zed\Auth\Persistence\AuthQueryContainerInterface getQueryContainer()
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

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityOauthUser\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\SecurityOauthUser\Communication\SecurityOauthUserCommunicationFactory getFactory()
 * @method \Spryker\Zed\SecurityOauthUser\Business\SecurityOauthUserFacadeInterface getFacade()
 */
class LoginController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    public function indexAction(Request $request)
    {
        //action will never be executed used as an entry point for Oauth login.
    }
}

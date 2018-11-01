<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Permission\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\Permission\Business\PermissionFacadeInterface getFacade()
 */
class IndexController extends AbstractController
{
    protected const MESSAGE_SUCCESS_SYNC = 'Permission plugins have been synchronized';
    protected const URL_REDIRECT = '/';

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function syncAction()
    {
        $this->getFacade()->syncPermissionPlugins();
        $this->addSuccessMessage(static::MESSAGE_SUCCESS_SYNC);

        return $this->redirectResponse(static::URL_REDIRECT);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method \Spryker\Zed\User\Business\UserFacadeInterface getFacade()
 * @method \Spryker\Zed\User\Persistence\UserQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\User\Communication\UserCommunicationFactory getFactory()
 */
class SessionController extends AbstractController
{
    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function updateTtlAction(): JsonResponse
    {
        if ($this->getFacade()->updateUserSessionTtl()) {
            return $this->jsonResponse([
                'success' => true,
            ]);
        }

        return $this->jsonResponse([
            'success' => false,
        ]);
    }
}

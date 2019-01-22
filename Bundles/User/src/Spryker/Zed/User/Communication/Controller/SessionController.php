<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Communication\Controller;

use DateTime;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Session\SessionConstants;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method \Spryker\Zed\User\Communication\UserCommunicationFactory getFactory()
 * @method \Spryker\Zed\User\Business\UserFacadeInterface getFacade()
 * @method \Spryker\Zed\User\Persistence\UserQueryContainerInterface getQueryContainer()
 */
class SessionController extends AbstractController
{
    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function infoAction(): JsonResponse
    {
        $metadata = $this->getFacade()
            ->getSessionMetadata();

        return $this->jsonResponse([
            'created' => $metadata->getCreated(),
            'lifetime' => (int)Config::get(SessionConstants::ZED_SESSION_TIME_TO_LIVE),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function updateTtlAction(): JsonResponse
    {
        $this->getFacade()
            ->updateSessionTtl();

        return $this->jsonResponse([
            'created' => (new DateTime())->getTimestamp(),
            'lifetime' => (int)Config::get(SessionConstants::ZED_SESSION_TIME_TO_LIVE),
        ]);
    }
}

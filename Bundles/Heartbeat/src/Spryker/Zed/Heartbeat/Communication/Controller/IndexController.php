<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Heartbeat\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Zed\Heartbeat\Business\HeartbeatFacade getFacade()
 */
class IndexController extends AbstractController
{
    const SYSTEM_UP = 'UP';
    const SYSTEM_DOWN = 'DOWN';
    const SYSTEM_STATUS = 'status';
    const STATUS_REPORT = 'report';

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction()
    {
        if ($this->getFacade()->isSystemAlive()) {
            return $this->jsonResponse(
                [self::SYSTEM_STATUS => self::SYSTEM_UP],
                Response::HTTP_OK
            );
        }

        return $this->jsonResponse(
            [self::SYSTEM_STATUS => self::SYSTEM_DOWN, self::STATUS_REPORT => $this->getFacade()->getReport()->toArray()],
            Response::HTTP_SERVICE_UNAVAILABLE
        );
    }
}

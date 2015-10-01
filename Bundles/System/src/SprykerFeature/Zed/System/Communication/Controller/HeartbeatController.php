<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\System\Communication\Controller;

use SprykerFeature\Shared\Library\Error\ErrorLogger;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\System\Communication\SystemDependencyContainer;
use SprykerFeature\Zed\System\SystemDependencyProvider;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method SystemDependencyContainer getDependencyContainer()
 */
class HeartbeatController extends AbstractController
{

    const SYSTEM_UP = 'UP';
    const SYSTEM_DOWN = 'DOWN';
    const SYSTEM_STATUS = 'status';
    const STATUS_REPORT = 'report';

    public function indexAction()
    {
        $heartbeatFacade = $this->getDependencyContainer()->createHeartbeatFacade();

        if ($heartbeatFacade->isSystemAlive()) {
            return $this->jsonResponse(
                [self::SYSTEM_STATUS => self::SYSTEM_UP],
                Response::HTTP_OK
            );
        } else {
            return $this->jsonResponse(
                [self::SYSTEM_STATUS => self::SYSTEM_DOWN, self::STATUS_REPORT => $heartbeatFacade->getReport()->toArray()],
                Response::HTTP_SERVICE_UNAVAILABLE
            );
        }
    }

}

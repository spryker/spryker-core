<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Heartbeat\Service;

use Generated\Client\Ide\FactoryAutoCompletion\HeartbeatService;
use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;
use SprykerFeature\Client\Heartbeat\HeartbeatDependencyProvider;
use SprykerFeature\Client\Heartbeat\Service\Zed\HeartbeatStubInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use SprykerFeature\Client\Heartbeat\Service\Storage\HeartbeatStorageInterface;

/**
 * @method HeartbeatService getFactory()
 */
class HeartbeatDependencyContainer extends AbstractServiceDependencyContainer
{

    /**
     * @return HeartbeatStubInterface
     */
    public function createZedStub()
    {
        $zedStub = $this->getProvidedDependency(HeartbeatDependencyProvider::SERVICE_ZED);
        $cartStub = $this->getFactory()->createZedHeartbeatStub(
            $zedStub
        );

        return $cartStub;
    }

}

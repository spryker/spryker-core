<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\SessionFile;

use SessionHandlerInterface;
use Spryker\Shared\SessionFile\Dependency\Service\SessionFileToMonitoringServiceInterface;
use Spryker\Shared\SessionFile\Handler\SessionHandlerFile;
use Spryker\Yves\Kernel\AbstractFactory;

/**
 * @method \Spryker\Yves\SessionFile\SessionFileConfig getConfig()
 */
class SessionFileFactory extends AbstractFactory
{
    /**
     * @return \SessionHandlerInterface
     */
    public function createSessionHandlerFile(): SessionHandlerInterface
    {
        return new SessionHandlerFile(
            $this->getConfig()->getSessionHandlerFileSavePath(),
            $this->getConfig()->getSessionLifetime(),
            $this->getMonitoringService()
        );
    }

    /**
     * @return \Spryker\Shared\SessionFile\Dependency\Service\SessionFileToMonitoringServiceInterface
     */
    public function getMonitoringService(): SessionFileToMonitoringServiceInterface
    {
        return $this->getProvidedDependency(SessionFileDependencyProvider::SERVICE_MONITORING);
    }
}

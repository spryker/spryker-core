<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SessionFile\Communication;

use SessionHandlerInterface;
use Spryker\Shared\SessionFile\Dependency\Service\SessionFileToMonitoringServiceInterface;
use Spryker\Shared\SessionFile\Handler\SessionHandlerFile;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SessionFile\SessionFileDependencyProvider;

/**
 * @method \Spryker\Zed\SessionFile\SessionFileConfig getConfig()
 */
class SessionFileCommunicationFactory extends AbstractCommunicationFactory
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

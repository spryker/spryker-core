<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\SessionFile;

use SessionHandlerInterface;
use Spryker\Shared\SessionFile\Builder\SessionEntityFileNameBuilder;
use Spryker\Shared\SessionFile\Builder\SessionEntityFileNameBuilderInterface;
use Spryker\Shared\SessionFile\Dependency\Service\SessionFileToMonitoringServiceInterface;
use Spryker\Shared\SessionFile\Handler\SessionHandlerFile;
use Spryker\Shared\SessionFile\Hasher\BcryptHasher;
use Spryker\Shared\SessionFile\Hasher\HasherInterface;
use Spryker\Shared\SessionFile\Saver\SessionEntitySaver;
use Spryker\Shared\SessionFile\Saver\SessionEntitySaverInterface;
use Spryker\Shared\SessionFile\Validator\SessionEntityValidator;
use Spryker\Shared\SessionFile\Validator\SessionEntityValidatorInterface;
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
            $this->getMonitoringService(),
        );
    }

    /**
     * @return \Spryker\Shared\SessionFile\Saver\SessionEntitySaverInterface
     */
    public function createSessionEntitySaver(): SessionEntitySaverInterface
    {
        return new SessionEntitySaver(
            $this->createBcryptHasher(),
            $this->createSessionEntityFileNameBuilder(),
        );
    }

    /**
     * @return \Spryker\Shared\SessionFile\Validator\SessionEntityValidatorInterface
     */
    public function createSessionEntityValidator(): SessionEntityValidatorInterface
    {
        return new SessionEntityValidator(
            $this->createBcryptHasher(),
            $this->createSessionEntityFileNameBuilder(),
        );
    }

    /**
     * @return \Spryker\Shared\SessionFile\Hasher\HasherInterface
     */
    public function createBcryptHasher(): HasherInterface
    {
        return new BcryptHasher();
    }

    /**
     * @return \Spryker\Shared\SessionFile\Builder\SessionEntityFileNameBuilderInterface
     */
    public function createSessionEntityFileNameBuilder(): SessionEntityFileNameBuilderInterface
    {
        return new SessionEntityFileNameBuilder(
            $this->getConfig()->getActiveSessionFilePath(),
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

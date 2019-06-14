<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Communication;

use Spryker\Shared\Application\EventListener\KernelLogListener;
use Spryker\Shared\Config\Environment;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\Application\ApplicationDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\EventListener\SaveSessionListener;

/**
 * @method \Spryker\Zed\Application\ApplicationConfig getConfig()
 * @method \Spryker\Zed\Application\Business\ApplicationFacadeInterface getFacade()
 */
class ApplicationCommunicationFactory extends AbstractCommunicationFactory
{
    use LoggerTrait;

    /**
     * @return \Spryker\Shared\Application\EventListener\KernelLogListener
     */
    public function createKernelLogListener()
    {
        return new KernelLogListener(
            $this->getLogger()
        );
    }

    /**
     * @return \Symfony\Component\EventDispatcher\EventSubscriberInterface
     */
    public function createSaveSessionEventSubscriber(): EventSubscriberInterface
    {
        return new SaveSessionListener();
    }

    /**
     * @return \Spryker\Shared\Config\Environment
     */
    public function getEnvironment(): Environment
    {
        return $this->getProvidedDependency(ApplicationDependencyProvider::ENVIRONMENT);
    }
}

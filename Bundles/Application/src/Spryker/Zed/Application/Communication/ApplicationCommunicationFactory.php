<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Communication;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Application\Application;
use Spryker\Shared\Application\ApplicationInterface;
use Spryker\Shared\Application\EventListener\KernelLogListener;
use Spryker\Shared\Kernel\Container\ContainerProxy;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Shared\Twig\TwigFunctionProvider;
use Spryker\Zed\Application\ApplicationDependencyProvider;
use Spryker\Zed\Application\Communication\EventListener\SaveSessionListener;
use Spryker\Zed\Application\Communication\Twig\YvesUrlFunctionProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig\TwigFunction;

/**
 * @method \Spryker\Zed\Application\ApplicationConfig getConfig()
 * @method \Spryker\Zed\Application\Business\ApplicationFacadeInterface getFacade()
 */
class ApplicationCommunicationFactory extends AbstractCommunicationFactory
{
    use LoggerTrait;

    /**
     * @return \Spryker\Shared\Application\ApplicationInterface
     */
    public function createApplication(): ApplicationInterface
    {
        return new Application($this->createServiceContainer(), $this->getApplicationPlugins());
    }

    /**
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function createServiceContainer(): ContainerInterface
    {
        return new ContainerProxy(['logger' => null, 'debug' => $this->getConfig()->isDebugModeEnabled(), 'charset' => 'UTF-8']);
    }

    /**
     * @return \Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface[]
     */
    public function getApplicationPlugins(): array
    {
        return $this->getProvidedDependency(ApplicationDependencyProvider::PLUGINS_APPLICATION);
    }

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
     * @deprecated Will be removed without replacement.
     *
     * @return \Symfony\Component\EventDispatcher\EventSubscriberInterface
     */
    public function createSaveSessionEventSubscriber(): EventSubscriberInterface
    {
        return new SaveSessionListener();
    }

    /**
     * @return \Spryker\Shared\Twig\TwigFunctionProvider
     */
    public function createYvesUrlFunctionProvider(): TwigFunctionProvider
    {
        return new YvesUrlFunctionProvider($this->getConfig());
    }

    /**
     * @return \Twig\TwigFunction
     */
    public function createYvesUrlFunction(): TwigFunction
    {
        $functionProvider = $this->createYvesUrlFunctionProvider();

        return new TwigFunction(
            $functionProvider->getFunctionName(),
            $functionProvider->getFunction(),
            $functionProvider->getOptions()
        );
    }
}

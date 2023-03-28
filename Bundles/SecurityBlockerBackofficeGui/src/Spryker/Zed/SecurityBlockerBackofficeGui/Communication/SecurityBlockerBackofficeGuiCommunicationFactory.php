<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityBlockerBackofficeGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SecurityBlockerBackofficeGui\Communication\Builder\MessageBuilder;
use Spryker\Zed\SecurityBlockerBackofficeGui\Communication\Builder\MessageBuilderInterface;
use Spryker\Zed\SecurityBlockerBackofficeGui\Communication\EventSubscriber\SecurityBlockerBackofficeUserEventSubscriber;
use Spryker\Zed\SecurityBlockerBackofficeGui\Dependency\Client\SecurityBlockerBackofficeGuiToSecurityBlockerClientInterface;
use Spryker\Zed\SecurityBlockerBackofficeGui\Dependency\Facade\SecurityBlockerBackofficeGuiToGlossaryFacadeInterface;
use Spryker\Zed\SecurityBlockerBackofficeGui\SecurityBlockerBackofficeGuiDependencyProvider;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @method \Spryker\Zed\SecurityBlockerBackofficeGui\SecurityBlockerBackofficeGuiConfig getConfig()
 */
class SecurityBlockerBackofficeGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Symfony\Component\EventDispatcher\EventSubscriberInterface
     */
    public function createSecurityBlockerBackOfficeUserEventSubscriber(): EventSubscriberInterface
    {
        return new SecurityBlockerBackofficeUserEventSubscriber(
            $this->getRequestStack(),
            $this->getSecurityBlockerClient(),
            $this->createMessageBuilder(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\SecurityBlockerBackofficeGui\Communication\Builder\MessageBuilderInterface
     */
    public function createMessageBuilder(): MessageBuilderInterface
    {
        return new MessageBuilder(
            $this->getGlossaryFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\SecurityBlockerBackofficeGui\Dependency\Client\SecurityBlockerBackofficeGuiToSecurityBlockerClientInterface
     */
    public function getSecurityBlockerClient(): SecurityBlockerBackofficeGuiToSecurityBlockerClientInterface
    {
        return $this->getProvidedDependency(SecurityBlockerBackofficeGuiDependencyProvider::CLIENT_SECURITY_BLOCKER);
    }

    /**
     * @return \Spryker\Zed\SecurityBlockerBackofficeGui\Dependency\Facade\SecurityBlockerBackofficeGuiToGlossaryFacadeInterface
     */
    public function getGlossaryFacade(): SecurityBlockerBackofficeGuiToGlossaryFacadeInterface
    {
        return $this->getProvidedDependency(SecurityBlockerBackofficeGuiDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RequestStack
     */
    public function getRequestStack(): RequestStack
    {
        return $this->getProvidedDependency(SecurityBlockerBackofficeGuiDependencyProvider::SERVICE_REQUEST_STACK);
    }
}

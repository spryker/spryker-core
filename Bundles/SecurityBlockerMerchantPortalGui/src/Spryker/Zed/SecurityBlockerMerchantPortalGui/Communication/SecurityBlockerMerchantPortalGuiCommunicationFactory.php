<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityBlockerMerchantPortalGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SecurityBlockerMerchantPortalGui\Communication\Builder\MessageBuilder;
use Spryker\Zed\SecurityBlockerMerchantPortalGui\Communication\Builder\MessageBuilderInterface;
use Spryker\Zed\SecurityBlockerMerchantPortalGui\Communication\EventSubscriber\SecurityBlockerMerchantPortalUserEventSubscriber;
use Spryker\Zed\SecurityBlockerMerchantPortalGui\Dependency\Client\SecurityBlockerMerchantPortalGuiToSecurityBlockerClientInterface;
use Spryker\Zed\SecurityBlockerMerchantPortalGui\Dependency\Facade\SecurityBlockerMerchantPortalGuiToGlossaryFacadeInterface;
use Spryker\Zed\SecurityBlockerMerchantPortalGui\SecurityBlockerMerchantPortalGuiDependencyProvider;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @method \Spryker\Zed\SecurityBlockerMerchantPortalGui\SecurityBlockerMerchantPortalGuiConfig getConfig()
 */
class SecurityBlockerMerchantPortalGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Symfony\Component\EventDispatcher\EventSubscriberInterface
     */
    public function createSecurityBlockerMerchantPortalUserEventSubscriber(): EventSubscriberInterface
    {
        return new SecurityBlockerMerchantPortalUserEventSubscriber(
            $this->getRequestStack(),
            $this->getSecurityBlockerClient(),
            $this->createMessageBuilder(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\SecurityBlockerMerchantPortalGui\Communication\Builder\MessageBuilderInterface
     */
    public function createMessageBuilder(): MessageBuilderInterface
    {
        return new MessageBuilder(
            $this->getGlossaryFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\SecurityBlockerMerchantPortalGui\Dependency\Client\SecurityBlockerMerchantPortalGuiToSecurityBlockerClientInterface
     */
    public function getSecurityBlockerClient(): SecurityBlockerMerchantPortalGuiToSecurityBlockerClientInterface
    {
        return $this->getProvidedDependency(SecurityBlockerMerchantPortalGuiDependencyProvider::CLIENT_SECURITY_BLOCKER);
    }

    /**
     * @return \Spryker\Zed\SecurityBlockerMerchantPortalGui\Dependency\Facade\SecurityBlockerMerchantPortalGuiToGlossaryFacadeInterface
     */
    public function getGlossaryFacade(): SecurityBlockerMerchantPortalGuiToGlossaryFacadeInterface
    {
        return $this->getProvidedDependency(SecurityBlockerMerchantPortalGuiDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RequestStack
     */
    public function getRequestStack(): RequestStack
    {
        return $this->getProvidedDependency(SecurityBlockerMerchantPortalGuiDependencyProvider::SERVICE_REQUEST_STACK);
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->getProvidedDependency(SecurityBlockerMerchantPortalGuiDependencyProvider::SERVICE_LOCALE);
    }
}

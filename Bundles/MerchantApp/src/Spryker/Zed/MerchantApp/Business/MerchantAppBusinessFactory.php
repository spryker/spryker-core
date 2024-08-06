<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantApp\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantApp\Business\MerchantAppOnboarding\MerchantAppOnboarding;
use Spryker\Zed\MerchantApp\Business\MerchantAppOnboarding\MerchantAppOnboardingInterface;
use Spryker\Zed\MerchantApp\Business\MerchantAppOnboarding\MerchantAppOnboardingStatus;
use Spryker\Zed\MerchantApp\Business\MerchantAppOnboarding\MerchantAppOnboardingStatusInterface;
use Spryker\Zed\MerchantApp\Business\MerchantAppOnboarding\MerchantAppOnboardingWriter;
use Spryker\Zed\MerchantApp\Business\MerchantAppOnboarding\MerchantAppOnboardingWriterInterface;
use Spryker\Zed\MerchantApp\Business\MessageBroker\MerchantAppOnboardingStatusChangedMessageHandler;
use Spryker\Zed\MerchantApp\Business\MessageBroker\MerchantAppOnboardingStatusChangedMessageHandlerInterface;
use Spryker\Zed\MerchantApp\Business\MessageBroker\ReadyForMerchantAppOnboardingMessageHandler;
use Spryker\Zed\MerchantApp\Business\MessageBroker\ReadyForMerchantAppOnboardingMessageHandlerInterface;
use Spryker\Zed\MerchantApp\Business\Request\Request;
use Spryker\Zed\MerchantApp\Business\Request\RequestInterface;
use Spryker\Zed\MerchantApp\Dependency\Facade\MerchantAppToKernelAppFacadeInterface;
use Spryker\Zed\MerchantApp\Dependency\Facade\MerchantAppToMerchantUserFacadeInterface;
use Spryker\Zed\MerchantApp\MerchantAppDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantApp\MerchantAppConfig getConfig()
 * @method \Spryker\Zed\MerchantApp\Persistence\MerchantAppEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantApp\Persistence\MerchantAppRepositoryInterface getRepository()
 */
class MerchantAppBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantApp\Business\MessageBroker\ReadyForMerchantAppOnboardingMessageHandlerInterface
     */
    public function createReadyForMerchantAppOnboardingMessageHandler(): ReadyForMerchantAppOnboardingMessageHandlerInterface
    {
        return new ReadyForMerchantAppOnboardingMessageHandler($this->createMerchantAppOnboardingDetails());
    }

    /**
     * @return \Spryker\Zed\MerchantApp\Business\MerchantAppOnboarding\MerchantAppOnboardingWriterInterface
     */
    public function createMerchantAppOnboardingDetails(): MerchantAppOnboardingWriterInterface
    {
        return new MerchantAppOnboardingWriter($this->getEntityManager());
    }

    /**
     * @return \Spryker\Zed\MerchantApp\Business\MerchantAppOnboarding\MerchantAppOnboardingStatusInterface
     */
    public function createMerchantAppOnboardingStatus(): MerchantAppOnboardingStatusInterface
    {
        return new MerchantAppOnboardingStatus($this->getEntityManager(), $this->getRepository());
    }

    /**
     * @return \Spryker\Zed\MerchantApp\Business\MessageBroker\MerchantAppOnboardingStatusChangedMessageHandlerInterface
     */
    public function createMerchantAppOnboardingChangedMessageHandler(): MerchantAppOnboardingStatusChangedMessageHandlerInterface
    {
        return new MerchantAppOnboardingStatusChangedMessageHandler($this->createMerchantAppOnboardingStatus());
    }

    /**
     * @return \Spryker\Zed\MerchantApp\Business\MerchantAppOnboarding\MerchantAppOnboardingInterface
     */
    public function createMerchantAppOnboarding(): MerchantAppOnboardingInterface
    {
        return new MerchantAppOnboarding($this->getConfig(), $this->getRepository(), $this->getEntityManager(), $this->getKernelAppFacade());
    }

    /**
     * @return \Spryker\Zed\MerchantApp\Dependency\Facade\MerchantAppToKernelAppFacadeInterface
     */
    public function getKernelAppFacade(): MerchantAppToKernelAppFacadeInterface
    {
        return $this->getProvidedDependency(MerchantAppDependencyProvider::FACADE_KERNEL_APP);
    }

    /**
     * @return \Spryker\Zed\MerchantApp\Business\Request\RequestInterface
     */
    public function createRequest(): RequestInterface
    {
        return new Request($this->getMerchantUserFacade());
    }

    /**
     * @return \Spryker\Zed\MerchantApp\Dependency\Facade\MerchantAppToMerchantUserFacadeInterface
     */
    public function getMerchantUserFacade(): MerchantAppToMerchantUserFacadeInterface
    {
        return $this->getProvidedDependency(MerchantAppDependencyProvider::FACADE_MERCHANT_USER);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business;

use Spryker\Zed\AvailabilityNotification\AvailabilityNotificationDependencyProvider;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionExistingChecker;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionExistingCheckerInterface;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionKeyGenerator;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionKeyGeneratorInterface;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionProcessor;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionProcessorInterface;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityUnsubscriptionProcessor;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityUnsubscriptionProcessorInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToLocaleFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToStoreFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Service\AvailabilityNotificationToUtilValidateServiceInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationRepositoryInterface getRepository()
 * @method \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationEntityManagerInterface getEntityManager()
 */
class AvailabilityNotificationBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionProcessorInterface
     */
    public function createAvailabilitySubscriptionProcessor(): AvailabilitySubscriptionProcessorInterface
    {
        return new AvailabilitySubscriptionProcessor(
            $this->getEntityManager(),
            $this->createAvailabilitySubscriptionExistingChecker(),
            $this->getMailFacade(),
            $this->getUtilValidateService(),
            $this->createSubscriptionKeyGenerator(),
            $this->getStoreFacade(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityUnsubscriptionProcessorInterface
     */
    public function createAvailabilityUnsubscriptionProcessor(): AvailabilityUnsubscriptionProcessorInterface
    {
        return new AvailabilityUnsubscriptionProcessor($this->getEntityManager());
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionExistingCheckerInterface
     */
    public function createAvailabilitySubscriptionExistingChecker(): AvailabilitySubscriptionExistingCheckerInterface
    {
        return new AvailabilitySubscriptionExistingChecker($this->getStoreFacade(), $this->getRepository());
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionKeyGeneratorInterface
     */
    protected function createSubscriptionKeyGenerator(): AvailabilitySubscriptionKeyGeneratorInterface
    {
        return new AvailabilitySubscriptionKeyGenerator();
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailFacadeInterface
     */
    protected function getMailFacade(): AvailabilityNotificationToMailFacadeInterface
    {
        return $this->getProvidedDependency(AvailabilityNotificationDependencyProvider::FACADE_MAIL);
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Dependency\Service\AvailabilityNotificationToUtilValidateServiceInterface
     */
    protected function getUtilValidateService(): AvailabilityNotificationToUtilValidateServiceInterface
    {
        return $this->getProvidedDependency(AvailabilityNotificationDependencyProvider::SERVICE_UTIL_VALIDATE);
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToStoreFacadeInterface
     */
    public function getStoreFacade(): AvailabilityNotificationToStoreFacadeInterface
    {
        return $this->getProvidedDependency(AvailabilityNotificationDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToLocaleFacadeInterface
     */
    public function getLocaleFacade(): AvailabilityNotificationToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(AvailabilityNotificationDependencyProvider::FACADE_LOCALE);
    }
}

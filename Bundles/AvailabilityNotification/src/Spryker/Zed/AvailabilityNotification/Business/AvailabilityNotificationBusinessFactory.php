<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business;

use Spryker\Zed\AvailabilityNotification\AvailabilityNotificationDependencyProvider;
use Spryker\Zed\AvailabilityNotification\Business\Anonymizer\SubscriptionAnonymizer;
use Spryker\Zed\AvailabilityNotification\Business\Anonymizer\SubscriptionAnonymizerInterface;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\SubscriptionHandler;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\SubscriptionHandlerInterface;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\SubscriptionKeyGenerator;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\SubscriptionKeyGeneratorInterface;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\SubscriptionManager;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\SubscriptionManagerInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Service\AvailabilityNotificationToUtilValidateServiceInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\AvailabilityNotification\AvailabilityNotificationConfig getConfig()
 * @method \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationQueryContainerInterface getQueryContainer()
 */
class AvailabilityNotificationBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\AvailabilityNotification\Business\Subscription\SubscriptionHandlerInterface
     */
    public function createSubscriptionHandler(): SubscriptionHandlerInterface
    {
        return new SubscriptionHandler(
            $this->createSubscriptionManager(),
            $this->getQueryContainer(),
            $this->getMailFacade(),
            $this->getUtilValidateService()
        );
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Business\Subscription\SubscriptionManagerInterface
     */
    protected function createSubscriptionManager(): SubscriptionManagerInterface
    {
        return new SubscriptionManager(
            $this->getQueryContainer(),
            $this->createSubscriptionKeyGenerator()
        );
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Business\Anonymizer\SubscriptionAnonymizerInterface
     */
    public function createSubscriptionAnonymizer(): SubscriptionAnonymizerInterface
    {
        return new SubscriptionAnonymizer(
            $this->getQueryContainer(),
            $this->createSubscriptionHandler()
        );
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Business\Subscription\SubscriptionKeyGeneratorInterface
     */
    protected function createSubscriptionKeyGenerator(): SubscriptionKeyGeneratorInterface
    {
        return new SubscriptionKeyGenerator();
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailInterface
     */
    protected function getMailFacade(): AvailabilityNotificationToMailInterface
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
}

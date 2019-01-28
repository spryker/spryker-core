<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business;

use Spryker\Zed\AvailabilityNotification\AvailabilityNotificationDependencyProvider;
use Spryker\Zed\AvailabilityNotification\Business\Anonymizer\AvailabilitySubscriptionAnonymizer;
use Spryker\Zed\AvailabilityNotification\Business\Anonymizer\AvailabilitySubscriptionAnonymizerInterface;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationSender;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationSenderInterface;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationSubscriber;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationSubscriberInterface;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationUnsubscriber;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationUnsubscriberInterface;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionFinder;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionFinderInterface;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionKeyGenerator;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionKeyGeneratorInterface;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionReader;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionReaderInterface;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionSaver;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionSaverInterface;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\UrlGenerator;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\UrlGeneratorInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToLocaleFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMoneyFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToPriceProductFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToProductFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToStoreFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Service\AvailabilityNotificationToUtilTextServiceInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Service\AvailabilityNotificationToUtilValidateServiceInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\AvailabilityNotification\AvailabilityNotificationConfig getConfig()
 * @method \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationRepositoryInterface getRepository()
 * @method \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationEntityManagerInterface getEntityManager()
 */
class AvailabilityNotificationBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationSubscriberInterface
     */
    public function createAvailabilityNotificationSubscriber(): AvailabilityNotificationSubscriberInterface
    {
        return new AvailabilityNotificationSubscriber(
            $this->createAvailabilitySubscriptionSaver(),
            $this->createAvailabilityNotificationSender(),
            $this->getUtilValidateService()
        );
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationUnsubscriberInterface
     */
    public function createAvailabilityNotificationUnsubscriber(): AvailabilityNotificationUnsubscriberInterface
    {
        return new AvailabilityNotificationUnsubscriber(
            $this->getEntityManager(),
            $this->createAvailabilityNotificationSender(),
            $this->getRepository(),
            $this->getProductFacade()
        );
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionFinderInterface
     */
    public function createAvailabilitySubscriptionFinder(): AvailabilitySubscriptionFinderInterface
    {
        return new AvailabilitySubscriptionFinder($this->createAvailabilityNotificationReader());
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionKeyGeneratorInterface
     */
    public function createSubscriptionKeyGenerator(): AvailabilitySubscriptionKeyGeneratorInterface
    {
        return new AvailabilitySubscriptionKeyGenerator($this->getUtilTextService());
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionSaverInterface
     */
    public function createAvailabilitySubscriptionSaver(): AvailabilitySubscriptionSaverInterface
    {
        return new AvailabilitySubscriptionSaver(
            $this->getEntityManager(),
            $this->createSubscriptionKeyGenerator(),
            $this->getStoreFacade(),
            $this->getLocaleFacade(),
            $this->createAvailabilityNotificationReader()
        );
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Business\Anonymizer\AvailabilitySubscriptionAnonymizerInterface
     */
    public function createSubscriptionAnonymizer(): AvailabilitySubscriptionAnonymizerInterface
    {
        return new AvailabilitySubscriptionAnonymizer(
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationSenderInterface
     */
    public function createAvailabilityNotificationSender(): AvailabilityNotificationSenderInterface
    {
        return new AvailabilityNotificationSender(
            $this->getMailFacade(),
            $this->getProductFacade(),
            $this->getMoneyFacade(),
            $this->getPriceProductFacade(),
            $this->createUrlGenerator(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionReaderInterface
     */
    public function createAvailabilityNotificationReader(): AvailabilitySubscriptionReaderInterface
    {
        return new AvailabilitySubscriptionReader(
            $this->getStoreFacade(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailFacadeInterface
     */
    public function getMailFacade(): AvailabilityNotificationToMailFacadeInterface
    {
        return $this->getProvidedDependency(AvailabilityNotificationDependencyProvider::FACADE_MAIL);
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Dependency\Service\AvailabilityNotificationToUtilValidateServiceInterface
     */
    public function getUtilValidateService(): AvailabilityNotificationToUtilValidateServiceInterface
    {
        return $this->getProvidedDependency(AvailabilityNotificationDependencyProvider::SERVICE_UTIL_VALIDATE);
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Dependency\Service\AvailabilityNotificationToUtilTextServiceInterface
     */
    public function getUtilTextService(): AvailabilityNotificationToUtilTextServiceInterface
    {
        return $this->getProvidedDependency(AvailabilityNotificationDependencyProvider::SERVICE_UTIL_TEXT);
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

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToProductFacadeInterface
     */
    public function getProductFacade(): AvailabilityNotificationToProductFacadeInterface
    {
        return $this->getProvidedDependency(AvailabilityNotificationDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMoneyFacadeInterface
     */
    public function getMoneyFacade(): AvailabilityNotificationToMoneyFacadeInterface
    {
        return $this->getProvidedDependency(AvailabilityNotificationDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToPriceProductFacadeInterface
     */
    public function getPriceProductFacade(): AvailabilityNotificationToPriceProductFacadeInterface
    {
        return $this->getProvidedDependency(AvailabilityNotificationDependencyProvider::FACADE_PRICE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Business\Subscription\UrlGeneratorInterface
     */
    protected function createUrlGenerator(): UrlGeneratorInterface
    {
        return new UrlGenerator($this->getConfig());
    }
}

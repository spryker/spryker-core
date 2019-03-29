<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business;

use Spryker\Zed\AvailabilityNotification\AvailabilityNotificationDependencyProvider;
use Spryker\Zed\AvailabilityNotification\Business\Anonymizer\AvailabilityNotificationSubscriptionAnonymizer;
use Spryker\Zed\AvailabilityNotification\Business\Anonymizer\AvailabilityNotificationSubscriptionAnonymizerInterface;
use Spryker\Zed\AvailabilityNotification\Business\CustomerExpander\CustomerExpander;
use Spryker\Zed\AvailabilityNotification\Business\CustomerExpander\CustomerExpanderInterface;
use Spryker\Zed\AvailabilityNotification\Business\Notification\AvailabilityNotificationSubscriptionSender;
use Spryker\Zed\AvailabilityNotification\Business\Notification\AvailabilityNotificationSubscriptionSenderInterface;
use Spryker\Zed\AvailabilityNotification\Business\Notification\AvailabilityNotificationUnsubscriptionSender;
use Spryker\Zed\AvailabilityNotification\Business\Notification\AvailabilityNotificationUnsubscriptionSenderInterface;
use Spryker\Zed\AvailabilityNotification\Business\Notification\ProductBecomeAvailableNotificationSender;
use Spryker\Zed\AvailabilityNotification\Business\Notification\ProductBecomeAvailableNotificationSenderInterface;
use Spryker\Zed\AvailabilityNotification\Business\Product\ProductAttributeFinder;
use Spryker\Zed\AvailabilityNotification\Business\Product\ProductAttributeFinderInterface;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationSubscriber;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationSubscriberInterface;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationSubscriptionKeyGenerator;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationSubscriptionKeyGeneratorInterface;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationSubscriptionReader;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationSubscriptionReaderInterface;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationSubscriptionSaver;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationSubscriptionSaverInterface;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationUnsubscriber;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationUnsubscriberInterface;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\UrlGenerator;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\UrlGeneratorInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToLocaleFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailFacadeInterface;
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
            $this->createAvailabilityNotificationSubscriptionSaver(),
            $this->createAvailabilityNotificationSubscriptionSender(),
            $this->getUtilValidateService(),
            $this->createAvailabilityNotificationReader()
        );
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationUnsubscriberInterface
     */
    public function createAvailabilityNotificationUnsubscriber(): AvailabilityNotificationUnsubscriberInterface
    {
        return new AvailabilityNotificationUnsubscriber(
            $this->getEntityManager(),
            $this->createAvailabilityNotificationUnsubscriptionSender(),
            $this->createAvailabilityNotificationReader()
        );
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationSubscriptionKeyGeneratorInterface
     */
    public function createSubscriptionKeyGenerator(): AvailabilityNotificationSubscriptionKeyGeneratorInterface
    {
        return new AvailabilityNotificationSubscriptionKeyGenerator($this->getUtilTextService());
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationSubscriptionSaverInterface
     */
    public function createAvailabilityNotificationSubscriptionSaver(): AvailabilityNotificationSubscriptionSaverInterface
    {
        return new AvailabilityNotificationSubscriptionSaver(
            $this->getEntityManager(),
            $this->createSubscriptionKeyGenerator(),
            $this->getStoreFacade(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Business\Anonymizer\AvailabilityNotificationSubscriptionAnonymizerInterface
     */
    public function createSubscriptionAnonymizer(): AvailabilityNotificationSubscriptionAnonymizerInterface
    {
        return new AvailabilityNotificationSubscriptionAnonymizer(
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Business\Notification\AvailabilityNotificationSubscriptionSenderInterface
     */
    public function createAvailabilityNotificationSubscriptionSender(): AvailabilityNotificationSubscriptionSenderInterface
    {
        return new AvailabilityNotificationSubscriptionSender(
            $this->getMailFacade(),
            $this->getProductFacade(),
            $this->createUrlGenerator(),
            $this->createProductAttributesFinder()
        );
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Business\Notification\AvailabilityNotificationUnsubscriptionSenderInterface
     */
    public function createAvailabilityNotificationUnsubscriptionSender(): AvailabilityNotificationUnsubscriptionSenderInterface
    {
        return new AvailabilityNotificationUnsubscriptionSender(
            $this->getMailFacade(),
            $this->getProductFacade(),
            $this->createUrlGenerator(),
            $this->createProductAttributesFinder()
        );
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Business\Notification\ProductBecomeAvailableNotificationSenderInterface
     */
    public function createProductBecomeAvailableNotificationSender(): ProductBecomeAvailableNotificationSenderInterface
    {
        return new ProductBecomeAvailableNotificationSender(
            $this->getMailFacade(),
            $this->getProductFacade(),
            $this->createUrlGenerator(),
            $this->getRepository(),
            $this->createProductAttributesFinder()
        );
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Business\Product\ProductAttributeFinderInterface
     */
    public function createProductAttributesFinder(): ProductAttributeFinderInterface
    {
        return new ProductAttributeFinder(
            $this->getProductFacade(),
            $this->createUrlGenerator()
        );
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationSubscriptionReaderInterface
     */
    public function createAvailabilityNotificationReader(): AvailabilityNotificationSubscriptionReaderInterface
    {
        return new AvailabilityNotificationSubscriptionReader(
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
     * @return \Spryker\Zed\AvailabilityNotification\Business\Subscription\UrlGeneratorInterface
     */
    protected function createUrlGenerator(): UrlGeneratorInterface
    {
        return new UrlGenerator($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Business\CustomerExpander\CustomerExpanderInterface
     */
    public function createCustomerExpander(): CustomerExpanderInterface
    {
        return new CustomerExpander($this->createAvailabilityNotificationReader());
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business;

use Spryker\Zed\AvailabilityNotification\AvailabilityNotificationDependencyProvider;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionChecker;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionCheckerInterface;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionKeyGenerator;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionKeyGeneratorInterface;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionMailProcessor;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionMailProcessorInterface;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionProcessor;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionProcessorInterface;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionSaver;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionSaverInterface;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityUnsubscriptionProcessor;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityUnsubscriptionProcessorInterface;
use Spryker\Zed\AvailabilityNotification\Communication\Plugin\AvailabilityNotificationSender;
use Spryker\Zed\AvailabilityNotification\Communication\Plugin\AvailabilityNotificationSenderInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToLocaleFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToPriceProductFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToProductFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToStoreFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Service\AvailabilityNotificationToUtilTextServiceInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Service\AvailabilityNotificationToUtilValidateServiceInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationRepositoryInterface getRepository()
 * @method \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\AvailabilityNotification\AvailabilityNotificationConfig getConfig()
 */
class AvailabilityNotificationBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionProcessorInterface
     */
    public function createAvailabilitySubscriptionProcessor(): AvailabilitySubscriptionProcessorInterface
    {
        return new AvailabilitySubscriptionProcessor(
            $this->createAvailabilitySubscriptionSaver(),
            $this->createAvailabilityNotificationSender(),
            $this->getUtilValidateService()
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
     * @return \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionCheckerInterface
     */
    public function createAvailabilitySubscriptionChecker(): AvailabilitySubscriptionCheckerInterface
    {
        return new AvailabilitySubscriptionChecker($this->getStoreFacade(), $this->getRepository());
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
            $this->createAvailabilitySubscriptionChecker()
        );
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Communication\Plugin\AvailabilityNotificationSenderInterface
     */
    public function createAvailabilityNotificationSender(): AvailabilityNotificationSenderInterface
    {
        return new AvailabilityNotificationSender($this->getMailFacade());
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionMailProcessorInterface
     */
    public function createAvailabilityNotificationMailProcessor(): AvailabilitySubscriptionMailProcessorInterface
    {
        return new AvailabilitySubscriptionMailProcessor(
            $this->getRepository(),
            $this->getMailFacade(),
            $this->getProductFacade(),
            $this->getPriceProductFacade(),
            $this->getConfig()
        );
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
     * @return \Spryker\Zed\AvailabilityNotification\Dependency\Service\AvailabilityNotificationToUtilTextServiceInterface
     */
    protected function getUtilTextService(): AvailabilityNotificationToUtilTextServiceInterface
    {
        return $this->getProvidedDependency(AvailabilityNotificationDependencyProvider::SERVICE_UTIL_TEXT);
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToStoreFacadeInterface
     */
    protected function getStoreFacade(): AvailabilityNotificationToStoreFacadeInterface
    {
        return $this->getProvidedDependency(AvailabilityNotificationDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToLocaleFacadeInterface
     */
    protected function getLocaleFacade(): AvailabilityNotificationToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(AvailabilityNotificationDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToProductFacadeInterface
     */
    protected function getProductFacade(): AvailabilityNotificationToProductFacadeInterface
    {
        return $this->getProvidedDependency(AvailabilityNotificationDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToPriceProductFacadeInterface
     */
    protected function getPriceProductFacade(): AvailabilityNotificationToPriceProductFacadeInterface
    {
        return $this->getProvidedDependency(AvailabilityNotificationDependencyProvider::FACADE_PRICE_PRODUCT);
    }
}

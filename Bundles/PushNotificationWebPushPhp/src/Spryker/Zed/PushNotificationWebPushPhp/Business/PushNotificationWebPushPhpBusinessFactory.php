<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotificationWebPushPhp\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Builder\MessageSentReportIdentifierBuilder;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Builder\MessageSentReportIdentifierBuilderInterface;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Creator\ErrorCreator;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Creator\ErrorCreatorInterface;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Creator\WebPushQueueCreator;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Creator\WebPushQueueCreatorInterface;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Expander\PushNotificationSubscriptionDeliveryLogExpander;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Expander\PushNotificationSubscriptionDeliveryLogExpanderInterface;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Filter\PushNotificationFilter;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Filter\PushNotificationFilterInterface;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Installer\PushNotificationProviderInstaller;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Installer\PushNotificationProviderInstallerInterface;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Sender\PushNotificationSender;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Sender\PushNotificationSenderInterface;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Validator\PushNotificationPayloadLengthValidator;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Validator\PushNotificationPayloadLengthValidatorInterface;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Validator\PushNotificationSubscriptionPayloadStructureValidator;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Validator\PushNotificationSubscriptionPayloadStructureValidatorInterface;
use Spryker\Zed\PushNotificationWebPushPhp\Dependency\External\PushNotificationWebPushPhpToSubscriptionInterface;
use Spryker\Zed\PushNotificationWebPushPhp\Dependency\External\PushNotificationWebPushPhpToWebPushInterface;
use Spryker\Zed\PushNotificationWebPushPhp\Dependency\Facade\PushNotificationWebPushPhpToPushNotificationFacadeInterface;
use Spryker\Zed\PushNotificationWebPushPhp\Dependency\Service\PushNotificationWebPushPhpToUtilEncodingServiceInterface;
use Spryker\Zed\PushNotificationWebPushPhp\PushNotificationWebPushPhpDependencyProvider;

/**
 * @method \Spryker\Zed\PushNotificationWebPushPhp\PushNotificationWebPushPhpConfig getConfig()
 */
class PushNotificationWebPushPhpBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\PushNotificationWebPushPhp\Business\Validator\PushNotificationSubscriptionPayloadStructureValidatorInterface
     */
    public function createPushNotificationSubscriptionPayloadStructureValidator(): PushNotificationSubscriptionPayloadStructureValidatorInterface
    {
        return new PushNotificationSubscriptionPayloadStructureValidator(
            $this->createErrorCreator(),
        );
    }

    /**
     * @return \Spryker\Zed\PushNotificationWebPushPhp\Business\Validator\PushNotificationPayloadLengthValidatorInterface
     */
    public function createPushNotificationPayloadLengthValidator(): PushNotificationPayloadLengthValidatorInterface
    {
        return new PushNotificationPayloadLengthValidator(
            $this->getUtilEncodingService(),
            $this->createErrorCreator(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\PushNotificationWebPushPhp\Business\Sender\PushNotificationSenderInterface
     */
    public function createPushNotificationCollectionSender(): PushNotificationSenderInterface
    {
        return new PushNotificationSender(
            $this->createPushNotificationFilter(),
            $this->createErrorCreator(),
            $this->createWebPushQueueCreator(),
            $this->createPushNotificationSubscriptionDeliveryLogExpander(),
            $this->createMessageSentReportIdentifierBuilder(),
        );
    }

    /**
     * @return \Spryker\Zed\PushNotificationWebPushPhp\Business\Installer\PushNotificationProviderInstallerInterface
     */
    public function createPushNotificationProviderInstaller(): PushNotificationProviderInstallerInterface
    {
        return new PushNotificationProviderInstaller(
            $this->getPushNotificationFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\PushNotificationWebPushPhp\Business\Filter\PushNotificationFilterInterface
     */
    public function createPushNotificationFilter(): PushNotificationFilterInterface
    {
        return new PushNotificationFilter();
    }

    /**
     * @return \Spryker\Zed\PushNotificationWebPushPhp\Business\Creator\ErrorCreatorInterface
     */
    public function createErrorCreator(): ErrorCreatorInterface
    {
        return new ErrorCreator();
    }

    /**
     * @return \Spryker\Zed\PushNotificationWebPushPhp\Dependency\External\PushNotificationWebPushPhpToSubscriptionInterface
     */
    public function getWebPushSubscription(): PushNotificationWebPushPhpToSubscriptionInterface
    {
        return $this->getProvidedDependency(PushNotificationWebPushPhpDependencyProvider::WEB_PUSH_SUBSCRIPTION);
    }

    /**
     * @return \Spryker\Zed\PushNotificationWebPushPhp\Business\Creator\WebPushQueueCreatorInterface
     */
    public function createWebPushQueueCreator(): WebPushQueueCreatorInterface
    {
        return new WebPushQueueCreator(
            $this->getWebPushSubscription(),
            $this->getWebPushNotificator(),
            $this->getUtilEncodingService(),
        );
    }

    /**
     * @return \Spryker\Zed\PushNotificationWebPushPhp\Business\Expander\PushNotificationSubscriptionDeliveryLogExpanderInterface
     */
    public function createPushNotificationSubscriptionDeliveryLogExpander(): PushNotificationSubscriptionDeliveryLogExpanderInterface
    {
        return new PushNotificationSubscriptionDeliveryLogExpander();
    }

    /**
     * @return \Spryker\Zed\PushNotificationWebPushPhp\Business\Builder\MessageSentReportIdentifierBuilderInterface
     */
    public function createMessageSentReportIdentifierBuilder(): MessageSentReportIdentifierBuilderInterface
    {
        return new MessageSentReportIdentifierBuilder();
    }

    /**
     * @return \Spryker\Zed\PushNotificationWebPushPhp\Dependency\External\PushNotificationWebPushPhpToWebPushInterface
     */
    public function getWebPushNotificator(): PushNotificationWebPushPhpToWebPushInterface
    {
        return $this->getProvidedDependency(PushNotificationWebPushPhpDependencyProvider::WEB_PUSH_NOTIFICATOR);
    }

    /**
     * @return \Spryker\Zed\PushNotificationWebPushPhp\Dependency\Service\PushNotificationWebPushPhpToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): PushNotificationWebPushPhpToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(PushNotificationWebPushPhpDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\PushNotificationWebPushPhp\Dependency\Facade\PushNotificationWebPushPhpToPushNotificationFacadeInterface
     */
    public function getPushNotificationFacade(): PushNotificationWebPushPhpToPushNotificationFacadeInterface
    {
        return $this->getProvidedDependency(PushNotificationWebPushPhpDependencyProvider::FACADE_PUSH_NOTIFICATION);
    }
}

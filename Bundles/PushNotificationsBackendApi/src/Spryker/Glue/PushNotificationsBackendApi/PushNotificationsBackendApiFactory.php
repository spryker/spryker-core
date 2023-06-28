<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PushNotificationsBackendApi;

use Spryker\Glue\Kernel\Backend\AbstractBackendApiFactory;
use Spryker\Glue\PushNotificationsBackendApi\Dependency\Client\PushNotificationsBackendApiToGlossaryStorageClientInterface;
use Spryker\Glue\PushNotificationsBackendApi\Dependency\Facade\PushNotificationsBackendApiToPushNotificationFacadeInterface;
use Spryker\Glue\PushNotificationsBackendApi\Processor\Creator\PushNotificationProviderCreator;
use Spryker\Glue\PushNotificationsBackendApi\Processor\Creator\PushNotificationProviderCreatorInterface;
use Spryker\Glue\PushNotificationsBackendApi\Processor\Creator\PushNotificationSubscriptionCreator;
use Spryker\Glue\PushNotificationsBackendApi\Processor\Creator\PushNotificationSubscriptionCreatorInterface;
use Spryker\Glue\PushNotificationsBackendApi\Processor\Deleter\PushNotificationProviderDeleter;
use Spryker\Glue\PushNotificationsBackendApi\Processor\Deleter\PushNotificationProviderDeleterInterface;
use Spryker\Glue\PushNotificationsBackendApi\Processor\Mapper\PushNotificationProviderMapper;
use Spryker\Glue\PushNotificationsBackendApi\Processor\Mapper\PushNotificationProviderMapperInterface;
use Spryker\Glue\PushNotificationsBackendApi\Processor\Mapper\PushNotificationSubscriptionMapper;
use Spryker\Glue\PushNotificationsBackendApi\Processor\Mapper\PushNotificationSubscriptionMapperInterface;
use Spryker\Glue\PushNotificationsBackendApi\Processor\Reader\PushNotificationProviderReader;
use Spryker\Glue\PushNotificationsBackendApi\Processor\Reader\PushNotificationProviderReaderInterface;
use Spryker\Glue\PushNotificationsBackendApi\Processor\ResponseBuilder\ErrorResponseBuilder;
use Spryker\Glue\PushNotificationsBackendApi\Processor\ResponseBuilder\ErrorResponseBuilderInterface;
use Spryker\Glue\PushNotificationsBackendApi\Processor\ResponseBuilder\PushNotificationProviderResponseBuilder;
use Spryker\Glue\PushNotificationsBackendApi\Processor\ResponseBuilder\PushNotificationProviderResponseBuilderInterface;
use Spryker\Glue\PushNotificationsBackendApi\Processor\ResponseBuilder\PushNotificationSubscriptionResponseBuilder;
use Spryker\Glue\PushNotificationsBackendApi\Processor\ResponseBuilder\PushNotificationSubscriptionResponseBuilderInterface;
use Spryker\Glue\PushNotificationsBackendApi\Processor\Translator\PushNotificationTranslator;
use Spryker\Glue\PushNotificationsBackendApi\Processor\Translator\PushNotificationTranslatorInterface;
use Spryker\Glue\PushNotificationsBackendApi\Processor\Updater\PushNotificationProviderUpdater;
use Spryker\Glue\PushNotificationsBackendApi\Processor\Updater\PushNotificationProviderUpdaterInterface;

/**
 * @method \Spryker\Glue\PushNotificationsBackendApi\PushNotificationsBackendApiConfig getConfig()
 */
class PushNotificationsBackendApiFactory extends AbstractBackendApiFactory
{
    /**
     * @return \Spryker\Glue\PushNotificationsBackendApi\Processor\Creator\PushNotificationSubscriptionCreatorInterface
     */
    public function createPushNotificationSubscriptionCreator(): PushNotificationSubscriptionCreatorInterface
    {
        return new PushNotificationSubscriptionCreator(
            $this->getPushNotificationFacade(),
            $this->createPushNotificationSubscriptionMapper(),
            $this->createPushNotificationSubscriptionResponseBuilder(),
            $this->createErrorResponseBuilder(),
        );
    }

    /**
     * @return \Spryker\Glue\PushNotificationsBackendApi\Processor\ResponseBuilder\PushNotificationSubscriptionResponseBuilderInterface
     */
    public function createPushNotificationSubscriptionResponseBuilder(): PushNotificationSubscriptionResponseBuilderInterface
    {
        return new PushNotificationSubscriptionResponseBuilder(
            $this->getConfig(),
            $this->createPushNotificationSubscriptionMapper(),
        );
    }

    /**
     * @return \Spryker\Glue\PushNotificationsBackendApi\Processor\Mapper\PushNotificationSubscriptionMapperInterface
     */
    public function createPushNotificationSubscriptionMapper(): PushNotificationSubscriptionMapperInterface
    {
        return new PushNotificationSubscriptionMapper();
    }

    /**
     * @return \Spryker\Glue\PushNotificationsBackendApi\Processor\Reader\PushNotificationProviderReaderInterface
     */
    public function createPushNotificationProviderReader(): PushNotificationProviderReaderInterface
    {
        return new PushNotificationProviderReader(
            $this->getPushNotificationFacade(),
            $this->createPushNotificationProviderResponseBuilder(),
            $this->createErrorResponseBuilder(),
        );
    }

    /**
     * @return \Spryker\Glue\PushNotificationsBackendApi\Processor\Creator\PushNotificationProviderCreatorInterface
     */
    public function createPushNotificationProviderCreator(): PushNotificationProviderCreatorInterface
    {
        return new PushNotificationProviderCreator(
            $this->getPushNotificationFacade(),
            $this->createPushNotificationProviderMapper(),
            $this->createPushNotificationProviderResponseBuilder(),
            $this->createErrorResponseBuilder(),
        );
    }

    /**
     * @return \Spryker\Glue\PushNotificationsBackendApi\Processor\Updater\PushNotificationProviderUpdaterInterface
     */
    public function createPushNotificationProviderUpdater(): PushNotificationProviderUpdaterInterface
    {
        return new PushNotificationProviderUpdater(
            $this->getPushNotificationFacade(),
            $this->createPushNotificationProviderMapper(),
            $this->createPushNotificationProviderResponseBuilder(),
            $this->createErrorResponseBuilder(),
            $this->createPushNotificationProviderReader(),
        );
    }

    /**
     * @return \Spryker\Glue\PushNotificationsBackendApi\Processor\Deleter\PushNotificationProviderDeleterInterface
     */
    public function createPushNotificationProviderDeleter(): PushNotificationProviderDeleterInterface
    {
        return new PushNotificationProviderDeleter(
            $this->getPushNotificationFacade(),
            $this->createPushNotificationProviderMapper(),
            $this->createPushNotificationProviderResponseBuilder(),
            $this->createErrorResponseBuilder(),
            $this->createPushNotificationProviderReader(),
        );
    }

    /**
     * @return \Spryker\Glue\PushNotificationsBackendApi\Processor\Mapper\PushNotificationProviderMapperInterface
     */
    public function createPushNotificationProviderMapper(): PushNotificationProviderMapperInterface
    {
        return new PushNotificationProviderMapper();
    }

    /**
     * @return \Spryker\Glue\PushNotificationsBackendApi\Processor\ResponseBuilder\PushNotificationProviderResponseBuilderInterface
     */
    public function createPushNotificationProviderResponseBuilder(): PushNotificationProviderResponseBuilderInterface
    {
        return new PushNotificationProviderResponseBuilder(
            $this->getConfig(),
            $this->createPushNotificationProviderMapper(),
        );
    }

    /**
     * @return \Spryker\Glue\PushNotificationsBackendApi\Processor\ResponseBuilder\ErrorResponseBuilderInterface
     */
    public function createErrorResponseBuilder(): ErrorResponseBuilderInterface
    {
        return new ErrorResponseBuilder(
            $this->getConfig(),
            $this->createPushNotificationTranslator(),
        );
    }

    /**
     * @return \Spryker\Glue\PushNotificationsBackendApi\Processor\Translator\PushNotificationTranslatorInterface
     */
    public function createPushNotificationTranslator(): PushNotificationTranslatorInterface
    {
        return new PushNotificationTranslator(
            $this->getGlossaryStorageClient(),
        );
    }

    /**
     * @return \Spryker\Glue\PushNotificationsBackendApi\Dependency\Facade\PushNotificationsBackendApiToPushNotificationFacadeInterface
     */
    public function getPushNotificationFacade(): PushNotificationsBackendApiToPushNotificationFacadeInterface
    {
        return $this->getProvidedDependency(PushNotificationsBackendApiDependencyProvider::FACADE_PUSH_NOTIFICATION);
    }

    /**
     * @return \Spryker\Glue\PushNotificationsBackendApi\Dependency\Client\PushNotificationsBackendApiToGlossaryStorageClientInterface
     */
    public function getGlossaryStorageClient(): PushNotificationsBackendApiToGlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(PushNotificationsBackendApiDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }
}

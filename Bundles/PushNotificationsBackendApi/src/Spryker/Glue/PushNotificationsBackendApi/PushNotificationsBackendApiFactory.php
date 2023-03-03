<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PushNotificationsBackendApi;

use Spryker\Glue\Kernel\Backend\AbstractBackendApiFactory;
use Spryker\Glue\PushNotificationsBackendApi\Dependency\Client\PushNotificationsBackendApiToGlossaryStorageClientInterface;
use Spryker\Glue\PushNotificationsBackendApi\Dependency\Facade\PushNotificationsBackendApiToPushNotificationFacadeInterface;
use Spryker\Glue\PushNotificationsBackendApi\Processor\Creator\PushNotificationSubscriptionCreator;
use Spryker\Glue\PushNotificationsBackendApi\Processor\Creator\PushNotificationSubscriptionCreatorInterface;
use Spryker\Glue\PushNotificationsBackendApi\Processor\Creator\ResponseCreator;
use Spryker\Glue\PushNotificationsBackendApi\Processor\Creator\ResponseCreatorInterface;
use Spryker\Glue\PushNotificationsBackendApi\Processor\Extractor\ErrorMessageExtractor;
use Spryker\Glue\PushNotificationsBackendApi\Processor\Extractor\ErrorMessageExtractorInterface;
use Spryker\Glue\PushNotificationsBackendApi\Processor\Mapper\PushNotificationSubscriptionResourceMapper;
use Spryker\Glue\PushNotificationsBackendApi\Processor\Mapper\PushNotificationSubscriptionResourceMapperInterface;

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
            $this->createPushNotificationSubscriptionResourceMapper(),
            $this->createResponseCreator(),
        );
    }

    /**
     * @return \Spryker\Glue\PushNotificationsBackendApi\Processor\Mapper\PushNotificationSubscriptionResourceMapperInterface
     */
    public function createPushNotificationSubscriptionResourceMapper(): PushNotificationSubscriptionResourceMapperInterface
    {
        return new PushNotificationSubscriptionResourceMapper();
    }

    /**
     * @return \Spryker\Glue\PushNotificationsBackendApi\Processor\Creator\ResponseCreatorInterface
     */
    public function createResponseCreator(): ResponseCreatorInterface
    {
        return new ResponseCreator(
            $this->createPushNotificationSubscriptionResourceMapper(),
            $this->getConfig(),
            $this->getGlossaryStorageClient(),
            $this->createErrorMessageExtractor(),
        );
    }

    /**
     * @return \Spryker\Glue\PushNotificationsBackendApi\Processor\Extractor\ErrorMessageExtractorInterface
     */
    public function createErrorMessageExtractor(): ErrorMessageExtractorInterface
    {
        return new ErrorMessageExtractor();
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

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AvailabilityNotificationsRestApi;

use Spryker\Glue\AvailabilityNotificationsRestApi\Dependency\Client\AvailabilityNotificationsRestApiToAvailabilityNotificationClientInterface;
use Spryker\Glue\AvailabilityNotificationsRestApi\Dependency\Client\AvailabilityNotificationsRestApiToStoreClientInterface;
use Spryker\Glue\AvailabilityNotificationsRestApi\Processor\Mapper\AvailabilityNotificationMapper;
use Spryker\Glue\AvailabilityNotificationsRestApi\Processor\Mapper\AvailabilityNotificationMapperInterface;
use Spryker\Glue\AvailabilityNotificationsRestApi\Processor\Reader\AvailabilityNotificationReader;
use Spryker\Glue\AvailabilityNotificationsRestApi\Processor\Reader\AvailabilityNotificationReaderInterface;
use Spryker\Glue\AvailabilityNotificationsRestApi\Processor\RestResponseBuilder\AvailabilityNotificationsRestResponseBuilder;
use Spryker\Glue\AvailabilityNotificationsRestApi\Processor\RestResponseBuilder\AvailabilityNotificationsRestResponseBuilderInterface;
use Spryker\Glue\AvailabilityNotificationsRestApi\Processor\Subscriber\AvailabilityNotificationSubscriber;
use Spryker\Glue\AvailabilityNotificationsRestApi\Processor\Subscriber\AvailabilityNotificationSubscriberInterface;
use Spryker\Glue\AvailabilityNotificationsRestApi\Processor\Validator\AvailabilityNotificationsRestApiValidator;
use Spryker\Glue\AvailabilityNotificationsRestApi\Processor\Validator\RestApiValidatorInterface;
use Spryker\Glue\Kernel\AbstractFactory;

/**
 * @method \Spryker\Glue\AvailabilityNotificationsRestApi\AvailabilityNotificationsRestApiConfig getConfig()
 */
class AvailabilityNotificationsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\AvailabilityNotificationsRestApi\Processor\Reader\AvailabilityNotificationReaderInterface
     */
    public function createAvailabilityNotificationReader(): AvailabilityNotificationReaderInterface
    {
        return new AvailabilityNotificationReader(
            $this->getAvailabilityNotificationClient(),
            $this->createAvailabilityNotificationsRestResponseBuilder(),
            $this->getStoreClient(),
            $this->createRestApiValidator()
        );
    }

    /**
     * @return \Spryker\Glue\AvailabilityNotificationsRestApi\Processor\Subscriber\AvailabilityNotificationSubscriberInterface
     */
    public function createAvailabilityNotificationSubscriber(): AvailabilityNotificationSubscriberInterface
    {
        return new AvailabilityNotificationSubscriber(
            $this->getAvailabilityNotificationClient(),
            $this->getStoreClient(),
            $this->createAvailabilityNotificationsRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\AvailabilityNotificationsRestApi\Dependency\Client\AvailabilityNotificationsRestApiToAvailabilityNotificationClientInterface
     */
    public function getAvailabilityNotificationClient(): AvailabilityNotificationsRestApiToAvailabilityNotificationClientInterface
    {
        return $this->getProvidedDependency(AvailabilityNotificationsRestApiDependencyProvider::CLIENT_AVAILABILITY_NOTIFICATION);
    }

    /**
     * @return \Spryker\Glue\AvailabilityNotificationsRestApi\Dependency\Client\AvailabilityNotificationsRestApiToStoreClientInterface
     */
    public function getStoreClient(): AvailabilityNotificationsRestApiToStoreClientInterface
    {
        return $this->getProvidedDependency(AvailabilityNotificationsRestApiDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Spryker\Glue\AvailabilityNotificationsRestApi\Processor\RestResponseBuilder\AvailabilityNotificationsRestResponseBuilderInterface
     */
    public function createAvailabilityNotificationsRestResponseBuilder(): AvailabilityNotificationsRestResponseBuilderInterface
    {
        return new AvailabilityNotificationsRestResponseBuilder(
            $this->getResourceBuilder(),
            $this->createAvailabilityNotificationMapper(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Glue\AvailabilityNotificationsRestApi\Processor\Mapper\AvailabilityNotificationMapperInterface
     */
    public function createAvailabilityNotificationMapper(): AvailabilityNotificationMapperInterface
    {
        return new AvailabilityNotificationMapper();
    }

    /**
     * @return \Spryker\Glue\AvailabilityNotificationsRestApi\Processor\Validator\RestApiValidatorInterface
     */
    public function createRestApiValidator(): RestApiValidatorInterface
    {
        return new AvailabilityNotificationsRestApiValidator();
    }
}

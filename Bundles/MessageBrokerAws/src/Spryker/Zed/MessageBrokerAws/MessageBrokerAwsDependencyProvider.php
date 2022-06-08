<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBrokerAws;

use Aws\Sns\SnsClient;
use Aws\Sqs\SqsClient;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MessageBrokerAws\Dependency\Facade\MessageBrokerAwsToStoreBridge;
use Spryker\Zed\MessageBrokerAws\Dependency\Service\MessageBrokerAwsToUtilEncodingServiceBridge;

/**
 * @method \Spryker\Zed\MessageBrokerAws\MessageBrokerAwsConfig getConfig()
 */
class MessageBrokerAwsDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_AWS_SQS = 'CLIENT_AWS_SQS';

    /**
     * @var string
     */
    public const CLIENT_AWS_SNS = 'CLIENT_AWS_SNS';

    /**
     * @var string
     */
    public const FACADE_STORE = 'FACADE_STORE';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addSqsAwsClient($container);
        $container = $this->addSnsAwsClient($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addUtilEncodingService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSqsAwsClient(Container $container): Container
    {
        $container->set(static::CLIENT_AWS_SQS, function () {
            return new SqsClient([
                'credentials' => [
                    'key' => $this->getConfig()->getSqsAwsAccessKey(),
                    'secret' => $this->getConfig()->getSqsAwsAccessSecret(),
                ],
                'endpoint' => $this->getConfig()->getSqsAwsEndpoint(),
                'region' => $this->getConfig()->getSqsAwsRegion(),
                'version' => $this->getConfig()->getSqsAwsVersion(),
            ]);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSnsAwsClient(Container $container): Container
    {
        $container->set(static::CLIENT_AWS_SNS, function () {
            return new SnsClient([
                'credentials' => [
                    'key' => $this->getConfig()->getSqsAwsAccessKey(),
                    'secret' => $this->getConfig()->getSqsAwsAccessSecret(),
                ],
                'endpoint' => $this->getConfig()->getSqsAwsEndpoint(),
                'region' => $this->getConfig()->getSqsAwsRegion(),
                'version' => '2010-03-31',
            ]);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container->set(static::FACADE_STORE, function (Container $container) {
            return new MessageBrokerAwsToStoreBridge($container->getLocator()->store()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new MessageBrokerAwsToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service(),
            );
        });

        return $container;
    }
}

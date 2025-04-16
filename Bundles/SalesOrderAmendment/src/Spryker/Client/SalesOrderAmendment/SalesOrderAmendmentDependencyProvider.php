<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SalesOrderAmendment;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\SalesOrderAmendment\Dependency\Client\SalesOrderAmendmentToMessengerClientBridge;
use Spryker\Client\SalesOrderAmendment\Dependency\Client\SalesOrderAmendmentToQuoteClientBridge;

class SalesOrderAmendmentDependencyProvider extends AbstractDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_QUOTE = 'CLIENT_QUOTE';

    /**
     * @var string
     */
    public const CLIENT_MESSENGER = 'CLIENT_MESSENGER';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addQuoteClient($container);
        $container = $this->addMessengerClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addQuoteClient(Container $container): Container
    {
        $container->set(static::CLIENT_QUOTE, function (Container $container) {
            return new SalesOrderAmendmentToQuoteClientBridge($container->getLocator()->quote()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addMessengerClient(Container $container): Container
    {
        $container->set(static::CLIENT_MESSENGER, function (Container $container) {
            return new SalesOrderAmendmentToMessengerClientBridge($container->getLocator()->messenger()->client());
        });

        return $container;
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCardMailConnector;

use Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToCustomerFacadeBridge;
use Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToMailFacadeBridge;
use Spryker\Zed\GiftCardMailConnector\Dependency\QueryContainer\GiftCardMailConnectorToGiftCardQueryContainerBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class GiftCardMailConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    const MAIL_FACADE = 'MAIL_FACADE';
    const CUSTOMER_FACADE = 'CUSTOMER_FACADE';
    const GIFT_CARD_QUERY_CONTAINER = 'GIFT_CARD_QUERY_CONTAINER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addMailFacade($container);
        $container = $this->addCustomerFacade($container);
        $container = $this->addGiftCardQueryContainer($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMailFacade(Container $container)
    {
        $container[static::MAIL_FACADE] = function (Container $container) {
            return new GiftCardMailConnectorToMailFacadeBridge($container->getLocator()->mail()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerFacade(Container $container)
    {
        $container[static::CUSTOMER_FACADE] = function (Container $container) {
            return new GiftCardMailConnectorToCustomerFacadeBridge($container->getLocator()->customer()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addGiftCardQueryContainer(Container $container)
    {
        $container[static::GIFT_CARD_QUERY_CONTAINER] = function (Container $container) {
            return new GiftCardMailConnectorToGiftCardQueryContainerBridge($container->getLocator()->giftCard()->queryContainer());
        };

        return $container;
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCardMailConnector;

use Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToCustomerFacadeBridge;
use Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToGiftCardFacadeBridge;
use Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToMailFacadeBridge;
use Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToSalesFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class GiftCardMailConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_MAIL = 'FACADE_MAIL';
    public const FACADE_CUSTOMER = 'FACADE_CUSTOMER';
    public const FACADE_GIFT_CARD = 'FACADE_GIFT_CARD';
    public const FACADE_SALES = 'FACADE_SALES';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addMailFacade($container);
        $container = $this->addCustomerFacade($container);
        $container = $this->addGiftCardFacade($container);
        $container = $this->addSalesFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMailFacade(Container $container)
    {
        $container[static::FACADE_MAIL] = function (Container $container) {
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
        $container[static::FACADE_CUSTOMER] = function (Container $container) {
            return new GiftCardMailConnectorToCustomerFacadeBridge($container->getLocator()->customer()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addGiftCardFacade(Container $container)
    {
        $container[static::FACADE_GIFT_CARD] = function (Container $container) {
            return new GiftCardMailConnectorToGiftCardFacadeBridge($container->getLocator()->giftCard()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesFacade(Container $container)
    {
        $container[static::FACADE_SALES] = function (Container $container) {
            return new GiftCardMailConnectorToSalesFacadeBridge($container->getLocator()->sales()->facade());
        };

        return $container;
    }
}

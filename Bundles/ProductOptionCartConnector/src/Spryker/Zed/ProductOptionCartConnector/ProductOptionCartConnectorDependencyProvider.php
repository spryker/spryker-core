<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionCartConnector;

use Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductOptionCartConnector\Dependency\Facade\ProductOptionCartConnectorToMessengerFacadeBridge;
use Spryker\Zed\ProductOptionCartConnector\Dependency\Facade\ProductOptionCartConnectorToPriceFacadeBridge;
use Spryker\Zed\ProductOptionCartConnector\Dependency\Facade\ProductOptionCartConnectorToProductOptionFacadeBridge;

/**
 * @method \Spryker\Zed\ProductOptionCartConnector\ProductOptionCartConnectorConfig getConfig()
 */
class ProductOptionCartConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_PRODUCT_OPTION = 'FACADE_PRODUCT_OPTION';

    /**
     * @var string
     */
    public const FACADE_PRICE = 'FACADE_PRICE';

    /**
     * @var string
     */
    public const FACADE_MESSENGER = 'FACADE_MESSENGER';

    /**
     * @var string
     */
    public const PROPEL_QUERY_PRODUCT_OPTION_VALUE = 'PROPEL_QUERY_PRODUCT_OPTION_VALUE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addPriceFacade($container);
        $container = $this->addProductOptionFacade($container);
        $container = $this->addMessengerFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = $this->addProductOptionValuePropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductOptionFacade(Container $container)
    {
        $container->set(static::FACADE_PRODUCT_OPTION, function (Container $container) {
            return new ProductOptionCartConnectorToProductOptionFacadeBridge($container->getLocator()->productOption()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceFacade(Container $container)
    {
        $container->set(static::FACADE_PRICE, function (Container $container) {
            return new ProductOptionCartConnectorToPriceFacadeBridge($container->getLocator()->price()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMessengerFacade(Container $container): Container
    {
        $container->set(static::FACADE_MESSENGER, function (Container $container) {
            return new ProductOptionCartConnectorToMessengerFacadeBridge(
                $container->getLocator()->messenger()->facade(),
            );
        });

        return $container;
    }

    /**
     * @module ProductOption
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductOptionValuePropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PRODUCT_OPTION_VALUE, $container->factory(function () {
            return SpyProductOptionValueQuery::create();
        }));

        return $container;
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi;

use Orm\Zed\Quote\Persistence\SpyQuoteQuery;
use Spryker\Zed\CartsRestApi\Business\Exception\MissingQuoteCollectionReaderPluginException;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToQuoteFacadeBridge;
use Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CartsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_QUOTE = 'FACADE_QUOTE';
    public const PLUGIN_QUOTE_COLLECTION_READER = 'PLUGIN_QUOTE_COLLECTION_READER';
    public const PROPEL_QUERY_QUOTE = 'PROPEL_QUERY_QUOTE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addQuoteFacade($container);
        $container = $this->addQuoteCollectionReaderPlugin($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);
        $container = $this->addQuotePropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuotePropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_QUOTE] = function () {
            return SpyQuoteQuery::create();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuoteFacade(Container $container): Container
    {
        $container[static::FACADE_QUOTE] = function (Container $container) {
            return new CartsRestApiToQuoteFacadeBridge($container->getLocator()->quote()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuoteCollectionReaderPlugin(Container $container): Container
    {
        $container[static::PLUGIN_QUOTE_COLLECTION_READER] = function () {
            return $this->getQuoteCollectionReaderPlugin();
        };

        return $container;
    }

    /**
     * @throws \Spryker\Zed\CartsRestApi\Business\Exception\MissingQuoteCollectionReaderPluginException
     *
     * @return \Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface
     */
    protected function getQuoteCollectionReaderPlugin(): QuoteCollectionReaderPluginInterface
    {
        throw new MissingQuoteCollectionReaderPluginException(sprintf(
            'Missing instance of %s! You need to configure CartQuoteCollectionReaderPlugin ' .
            'in your own CartsRestApiDependencyProvider::getQuoteCollectionReaderPlugin() ' .
            'to be able to read quote collection.',
            QuoteCollectionReaderPluginInterface::class
        ));
    }
}

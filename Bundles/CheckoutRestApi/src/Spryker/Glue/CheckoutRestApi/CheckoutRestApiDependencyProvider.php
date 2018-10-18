<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi;

use Spryker\Glue\CheckoutRestApi\Exception\ReaderNotImplementedException;
use Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

class CheckoutRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGIN_QUOTE_COLLECTION_READER = 'PLUGIN_QUOTE_COLLECTION_READER';

    protected const EXCEPTION_MESSAGE_READER_NOT_IMPLEMENTED = 'Reader not implemented on project level';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addQuoteCollectionReaderPlugin($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addQuoteCollectionReaderPlugin(Container $container): Container
    {
        $container[static::PLUGIN_QUOTE_COLLECTION_READER] = function (Container $container) {
            return $this->getQuoteCollectionReaderPlugin();
        };

        return $container;
    }

    /**
     * @throws \Spryker\Glue\CheckoutRestApi\Exception\ReaderNotImplementedException
     *
     * @return \Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface
     */
    protected function getQuoteCollectionReaderPlugin(): QuoteCollectionReaderPluginInterface
    {
        throw new ReaderNotImplementedException(static::EXCEPTION_MESSAGE_READER_NOT_IMPLEMENTED);
    }
}

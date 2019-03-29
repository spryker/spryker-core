<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentProduct;

use Spryker\Client\ContentProduct\Dependency\Client\ContentProductToContentStorageClientInterface;
use Spryker\Client\ContentProduct\Executor\ProductAbstractListTermToProductAbstractListTypeExecutor;
use Spryker\Client\ContentProduct\Executor\ProductAbstractListTermToProductAbstractListTypeExecutorInterface;
use Spryker\Client\ContentProduct\Resolver\ProductAbstractResolver;
use Spryker\Client\ContentProduct\Resolver\ProductAbstractResolverInterface;
use Spryker\Client\Kernel\AbstractFactory;

class ContentProductFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ContentProduct\Executor\ProductAbstractListTermToProductAbstractListTypeExecutorInterface
     */
    public function createProductAbstractListTermToProductAbstractListTypeExecutor(): ProductAbstractListTermToProductAbstractListTypeExecutorInterface
    {
        return new ProductAbstractListTermToProductAbstractListTypeExecutor();
    }

    /**
     * @return \Spryker\Client\ContentProduct\Resolver\ProductAbstractResolverInterface
     */
    public function createProductAbstractResolver(): ProductAbstractResolverInterface
    {
        return new ProductAbstractResolver();
    }

    /**
     * @return \Spryker\Client\ContentProduct\Dependency\Client\ContentProductToContentStorageClientInterface
     */
    public function getContentStorageClient(): ContentProductToContentStorageClientInterface
    {
        return $this->getProvidedDependency(ContentProductDependencyProvider::CLIENT_CONTENT_STORAGE);
    }
}

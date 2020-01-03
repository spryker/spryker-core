<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentProduct;

use Spryker\Client\ContentProduct\Dependency\Client\ContentProductToContentStorageClientInterface;
use Spryker\Client\ContentProduct\Executor\ContentProductTermExecutorInterface;
use Spryker\Client\ContentProduct\Executor\ProductAbstractListTermToProductAbstractListTypeExecutor;
use Spryker\Client\ContentProduct\Mapper\ContentProductAbstractListTypeMapper;
use Spryker\Client\ContentProduct\Mapper\ContentProductAbstractListTypeMapperInterface;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Shared\ContentProduct\ContentProductConfig;

/**
 * @method \Spryker\Shared\ContentProduct\ContentProductConfig getConfig()
 */
class ContentProductFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ContentProduct\Mapper\ContentProductAbstractListTypeMapperInterface
     */
    public function createContentProductAbstractListTypeMapper(): ContentProductAbstractListTypeMapperInterface
    {
        return new ContentProductAbstractListTypeMapper(
            $this->getContentStorageClient(),
            $this->getContentProductTermExecutorMap()
        );
    }

    /**
     * @return \Spryker\Client\ContentProduct\Executor\ContentProductTermExecutorInterface[]
     */
    public function getContentProductTermExecutorMap(): array
    {
        return [
            ContentProductConfig::CONTENT_TERM_PRODUCT_ABSTRACT_LIST => $this->createProductAbstractListTermToProductAbstractListTypeExecutor(),
        ];
    }

    /**
     * @return \Spryker\Client\ContentProduct\Executor\ContentProductTermExecutorInterface
     */
    public function createProductAbstractListTermToProductAbstractListTypeExecutor(): ContentProductTermExecutorInterface
    {
        return new ProductAbstractListTermToProductAbstractListTypeExecutor();
    }

    /**
     * @return \Spryker\Client\ContentProduct\Dependency\Client\ContentProductToContentStorageClientInterface
     */
    public function getContentStorageClient(): ContentProductToContentStorageClientInterface
    {
        return $this->getProvidedDependency(ContentProductDependencyProvider::CLIENT_CONTENT_STORAGE);
    }
}

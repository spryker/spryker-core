<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentProductSet;

use Spryker\Client\ContentProductSet\Dependency\Client\ContentProductSetToContentStorageClientInterface;
use Spryker\Client\ContentProductSet\Executor\ContentProductSetTermExecutorInterface;
use Spryker\Client\ContentProductSet\Executor\ProductSetTermToProductSetTypeExecutor;
use Spryker\Client\ContentProductSet\Mapper\ContentProductSetTypeMapper;
use Spryker\Client\ContentProductSet\Mapper\ContentProductSetTypeMapperInterface;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Shared\ContentProductSet\ContentProductSetConfig;

class ContentProductSetFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ContentProductSet\Mapper\ContentProductSetTypeMapperInterface
     */
    public function createContentProductSetTypeMapper(): ContentProductSetTypeMapperInterface
    {
        return new ContentProductSetTypeMapper(
            $this->getContentStorageClient(),
            $this->getContentProductSetTermExecutorMap()
        );
    }

    /**
     * @return \Spryker\Client\ContentProductSet\Executor\ContentProductSetTermExecutorInterface[]
     */
    public function getContentProductSetTermExecutorMap(): array
    {
        return [
            ContentProductSetConfig::CONTENT_TERM_PRODUCT_SET => $this->createProductSetTermToProductSetTypeExecutor(),
        ];
    }

    /**
     * @return \Spryker\Client\ContentProductSet\Executor\ContentProductSetTermExecutorInterface
     */
    public function createProductSetTermToProductSetTypeExecutor(): ContentProductSetTermExecutorInterface
    {
        return new ProductSetTermToProductSetTypeExecutor();
    }

    /**
     * @return \Spryker\Client\ContentProductSet\Dependency\Client\ContentProductSetToContentStorageClientInterface
     */
    public function getContentStorageClient(): ContentProductSetToContentStorageClientInterface
    {
        return $this->getProvidedDependency(ContentProductSetDependencyProvider::CLIENT_CONTENT_STORAGE);
    }
}

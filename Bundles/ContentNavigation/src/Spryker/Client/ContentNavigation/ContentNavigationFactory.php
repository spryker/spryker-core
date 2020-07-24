<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentNavigation;

use Spryker\Client\ContentNavigation\Dependency\Client\ContentNavigationToContentStorageClientInterface;
use Spryker\Client\ContentNavigation\Executor\ContentNavigationTermExecutorInterface;
use Spryker\Client\ContentNavigation\Executor\ContentTermToNavigationTypeExecutor;
use Spryker\Client\ContentNavigation\Mapper\ContentNavigationTypeMapper;
use Spryker\Client\ContentNavigation\Mapper\ContentNavigationTypeMapperInterface;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Shared\ContentNavigation\ContentNavigationConfig;

class ContentNavigationFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ContentNavigation\Mapper\ContentNavigationTypeMapperInterface
     */
    public function createContentNavigationTypeMapper(): ContentNavigationTypeMapperInterface
    {
        return new ContentNavigationTypeMapper(
            $this->getContentStorageClient(),
            $this->getContentNavigationTermExecutorMap()
        );
    }

    /**
     * @return \Spryker\Client\ContentNavigation\Executor\ContentNavigationTermExecutorInterface[]
     */
    public function getContentNavigationTermExecutorMap(): array
    {
        return [
            ContentNavigationConfig::CONTENT_TERM_NAVIGATION => $this->createContentNavigationTermExecutor(),
        ];
    }

    /**
     * @return \Spryker\Client\ContentNavigation\Executor\ContentNavigationTermExecutorInterface
     */
    public function createContentNavigationTermExecutor(): ContentNavigationTermExecutorInterface
    {
        return new ContentTermToNavigationTypeExecutor();
    }

    /**
     * @return \Spryker\Client\ContentNavigation\Dependency\Client\ContentNavigationToContentStorageClientInterface
     */
    public function getContentStorageClient(): ContentNavigationToContentStorageClientInterface
    {
        return $this->getProvidedDependency(ContentNavigationDependencyProvider::CLIENT_CONTENT_STORAGE);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentProduct;

use Spryker\Client\ContentProduct\Dependency\Client\ContentProductToContentStorageClientInterface;
use Spryker\Client\ContentProduct\Exception\InvalidProductAbstractListTypeException;
use Spryker\Client\ContentProduct\Executor\ContentProductTermExecutorInterface;
use Spryker\Client\Kernel\AbstractFactory;

/**
 * @method \Spryker\Client\ContentProduct\ContentProductConfig getConfig()
 */
class ContentProductFactory extends AbstractFactory
{
    /**
     * @param string $term
     *
     * @throws \Spryker\Client\ContentProduct\Exception\InvalidProductAbstractListTypeException
     *
     * @return \Spryker\Client\ContentProduct\Executor\ContentProductTermExecutorInterface
     */
    public function createContentProductTermExecutorByTerm(string $term): ContentProductTermExecutorInterface
    {
        if (!isset($this->getConfig()->getEnabledTermExecutors()[$term])) {
            throw new InvalidProductAbstractListTypeException(
                sprintf('There is no ContentProduct Term which can work with the term %s.', $term)
            );
        }

        $contentProductTermExecutor = $this->getConfig()->getEnabledTermExecutors()[$term];

        return new $contentProductTermExecutor();
    }

    /**
     * @return \Spryker\Client\ContentProduct\Dependency\Client\ContentProductToContentStorageClientInterface
     */
    public function getContentStorageClient(): ContentProductToContentStorageClientInterface
    {
        return $this->getProvidedDependency(ContentProductDependencyProvider::CLIENT_CONTENT_STORAGE);
    }
}

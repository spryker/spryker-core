<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MultiCartsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\MultiCartsRestApi\Dependency\Client\MultiCartsRestApiToMultiCartClientInterface;
use Spryker\Glue\MultiCartsRestApi\Dependency\Client\MultiCartsRestApiToPersistentCartClientInterface;
use Spryker\Glue\MultiCartsRestApi\Processor\Cart\MultipleQuoteCreator;
use Spryker\Glue\MultiCartsRestApi\Processor\Cart\MultipleQuoteCreatorInterface;
use Spryker\Glue\MultiCartsRestApi\Processor\Quote\MultipleQuoteCollectionReader;
use Spryker\Glue\MultiCartsRestApi\Processor\Quote\MultipleQuoteCollectionReaderInterface;

/**
 *
 * @method \Spryker\Glue\MultiCartsRestApi\MultiCartsRestApiConfig getConfig()
 */
class MultiCartsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\MultiCartsRestApi\Processor\Quote\MultipleQuoteCollectionReaderInterface
     */
    public function createMultipleQuoteCollectionReader(): MultipleQuoteCollectionReaderInterface
    {
        return new MultipleQuoteCollectionReader($this->getMultiCartClient());
    }

    /**
     * @return \Spryker\Glue\MultiCartsRestApi\Processor\Cart\MultipleQuoteCreatorInterface
     */
    public function createMultipleQuoteCreator(): MultipleQuoteCreatorInterface
    {
        return new MultipleQuoteCreator($this->getPersistentCartClient());
    }

    /**
     * @return \Spryker\Glue\MultiCartsRestApi\Dependency\Client\MultiCartsRestApiToMultiCartClientInterface
     */
    public function getMultiCartClient(): MultiCartsRestApiToMultiCartClientInterface
    {
        return $this->getProvidedDependency(MultiCartsRestApiDependencyProvider::MULTI_CLIENT_CART);
    }

    /**
     * @return \Spryker\Glue\MultiCartsRestApi\Dependency\Client\MultiCartsRestApiToPersistentCartClientInterface
     */
    public function getPersistentCartClient(): MultiCartsRestApiToPersistentCartClientInterface
    {
        return $this->getProvidedDependency(MultiCartsRestApiDependencyProvider::CLIENT_PERSISTENT_CART);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business;

use Spryker\Zed\CartsRestApi\Business\Cart\CartCreator;
use Spryker\Zed\CartsRestApi\Business\Cart\CartCreatorInterface;
use Spryker\Zed\CartsRestApi\Business\Cart\CartDeleter;
use Spryker\Zed\CartsRestApi\Business\Cart\CartDeleterInterface;
use Spryker\Zed\CartsRestApi\Business\Cart\CartReader;
use Spryker\Zed\CartsRestApi\Business\Cart\CartReaderInterface;
use Spryker\Zed\CartsRestApi\Business\Cart\CartUpdater;
use Spryker\Zed\CartsRestApi\Business\Cart\CartUpdaterInterface;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteUuidWriter;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteUuidWriterInterface;
use Spryker\Zed\CartsRestApi\CartsRestApiDependencyProvider;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToCartFacadeInterface;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToPersistentCartFacadeInterface;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToQuoteFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CartsRestApi\Persistence\CartsRestApiEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CartsRestApi\CartsRestApiConfig getConfig()
 */
class CartsRestApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CartsRestApi\Business\Quote\QuoteUuidWriterInterface
     */
    public function createQuoteUuidWriter(): QuoteUuidWriterInterface
    {
        return new QuoteUuidWriter(
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\CartsRestApi\Business\Cart\CartReaderInterface
     */
    public function createCartReader(): CartReaderInterface
    {
        return new CartReader($this->getQuoteFacade());
    }

    /**
     * @return \Spryker\Zed\CartsRestApi\Business\Cart\CartCreatorInterface
     */
    public function createCartCreator(): CartCreatorInterface
    {
        return new CartCreator(
            $this->getPersistentCartFacade(),
            $this->createCartReader()
        );
    }

    /**
     * @return \Spryker\Zed\CartsRestApi\Business\Cart\CartDeleterInterface
     */
    public function createCartDeleter(): CartDeleterInterface
    {
        return new CartDeleter(
            $this->getPersistentCartFacade(),
            $this->createCartReader()
        );
    }

    /**
     * @return \Spryker\Zed\CartsRestApi\Business\Cart\CartUpdaterInterface
     */
    public function createQuoteUpdater(): CartUpdaterInterface
    {
        return new CartUpdater(
            $this->getPersistentCartFacade(),
            $this->getCartFacade(),
            $this->createCartReader()
        );
    }

    /**
     * @return \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToQuoteFacadeInterface
     */
    public function getQuoteFacade(): CartsRestApiToQuoteFacadeInterface
    {
        return $this->getProvidedDependency(CartsRestApiDependencyProvider::FACADE_QUOTE);
    }

    /**
     * @return \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToPersistentCartFacadeInterface
     */
    public function getPersistentCartFacade(): CartsRestApiToPersistentCartFacadeInterface
    {
        return $this->getProvidedDependency(CartsRestApiDependencyProvider::FACADE_PERSISTENT_CART);
    }

    /**
     * @return \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToCartFacadeInterface
     */
    public function getCartFacade(): CartsRestApiToCartFacadeInterface
    {
        return $this->getProvidedDependency(CartsRestApiDependencyProvider::FACADE_CART);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business;

use Spryker\Zed\CartsRestApi\Business\Quote\QuoteCreator;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteCreatorInterface;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteDeleter;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteDeleterInterface;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteReader;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteUpdater;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteUpdaterInterface;
use Spryker\Zed\CartsRestApi\Business\CartItem\CartItemAdder;
use Spryker\Zed\CartsRestApi\Business\CartItem\CartItemAdderInterface;
use Spryker\Zed\CartsRestApi\Business\CartItem\CartItemDeleter;
use Spryker\Zed\CartsRestApi\Business\CartItem\CartItemDeleterInterface;
use Spryker\Zed\CartsRestApi\Business\CartItem\CartItemUpdater;
use Spryker\Zed\CartsRestApi\Business\CartItem\CartItemUpdaterInterface;
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
     * @return \Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface
     */
    public function createQuoteReader(): QuoteReaderInterface
    {
        return new QuoteReader($this->getQuoteFacade());
    }

    /**
     * @return \Spryker\Zed\CartsRestApi\Business\Quote\QuoteCreatorInterface
     */
    public function createQuoteCreator(): QuoteCreatorInterface
    {
        return new QuoteCreator(
            $this->getPersistentCartFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CartsRestApi\Business\Quote\QuoteDeleterInterface
     */
    public function createQuoteDeleter(): QuoteDeleterInterface
    {
        return new QuoteDeleter(
            $this->getPersistentCartFacade(),
            $this->createQuoteReader()
        );
    }

    /**
     * @return \Spryker\Zed\CartsRestApi\Business\Quote\QuoteUpdaterInterface
     */
    public function createQuoteUpdater(): QuoteUpdaterInterface
    {
        return new QuoteUpdater(
            $this->getPersistentCartFacade(),
            $this->getCartFacade(),
            $this->createQuoteReader()
        );
    }

    /**
     * @return \Spryker\Zed\CartsRestApi\Business\CartItem\CartItemAdderInterface
     */
    public function createCartItemAdder(): CartItemAdderInterface
    {
        return new CartItemAdder(
            $this->getPersistentCartFacade(),
            $this->createQuoteReader()
        );
    }

    /**
     * @return \Spryker\Zed\CartsRestApi\Business\CartItem\CartItemDeleterInterface
     */
    public function createCartItemDeleter(): CartItemDeleterInterface
    {
        return new CartItemDeleter(
            $this->getPersistentCartFacade(),
            $this->createQuoteReader()
        );
    }

    /**
     * @return \Spryker\Zed\CartsRestApi\Business\CartItem\CartItemUpdaterInterface
     */
    public function createCartItemUpdater(): CartItemUpdaterInterface
    {
        return new CartItemUpdater(
            $this->getPersistentCartFacade(),
            $this->createQuoteReader()
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

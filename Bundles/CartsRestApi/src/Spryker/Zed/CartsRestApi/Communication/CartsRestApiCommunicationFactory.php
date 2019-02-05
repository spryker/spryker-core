<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Communication;

use Spryker\Zed\CartsRestApi\Business\Quote\Mapper\QuoteMapper;
use Spryker\Zed\CartsRestApi\Business\Quote\Mapper\QuoteMapperInterface;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteReader;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface;
use Spryker\Zed\CartsRestApi\Business\Quote\SingleQuoteCreator;
use Spryker\Zed\CartsRestApi\Business\Quote\SingleQuoteCreatorInterface;
use Spryker\Zed\CartsRestApi\CartsRestApiDependencyProvider;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToPersistentCartFacadeInterface;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToQuoteFacadeInterface;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToStoreFacadeInterface;
use Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\CartsRestApi\Persistence\CartsRestApiEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CartsRestApi\CartsRestApiConfig getConfig()
 * @method \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacadeInterface getFacade()
 */
class CartsRestApiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CartsRestApi\Business\Quote\SingleQuoteCreatorInterface
     */
    public function createSingleQuoteCreator(): SingleQuoteCreatorInterface
    {
        return new SingleQuoteCreator(
            $this->createQuoteReader(),
            $this->getPersistentCartFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface
     */
    public function createQuoteReader(): QuoteReaderInterface
    {
        return new QuoteReader(
            $this->getQuoteFacade(),
            $this->getStoreFacade(),
            $this->getQuoteCollectionReaderPlugins(),
            $this->createQuoteMapper()
        );
    }

    /**
     * @return \Spryker\Zed\CartsRestApi\Business\Quote\Mapper\QuoteMapperInterface
     */
    public function createQuoteMapper(): QuoteMapperInterface
    {
        return new QuoteMapper();
    }

    /**
     * @return \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToQuoteFacadeInterface
     */
    public function getQuoteFacade(): CartsRestApiToQuoteFacadeInterface
    {
        return $this->getProvidedDependency(CartsRestApiDependencyProvider::FACADE_QUOTE);
    }

    /**
     * @return \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToStoreFacadeInterface
     */
    public function getStoreFacade(): CartsRestApiToStoreFacadeInterface
    {
        return $this->getProvidedDependency(CartsRestApiDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToPersistentCartFacadeInterface
     */
    public function getPersistentCartFacade(): CartsRestApiToPersistentCartFacadeInterface
    {
        return $this->getProvidedDependency(CartsRestApiDependencyProvider::FACADE_PERSISTENT_CART);
    }

    /**
     * @return \Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface
     */
    public function getQuoteCollectionReaderPlugins(): QuoteCollectionReaderPluginInterface
    {
        return $this->getProvidedDependency(CartsRestApiDependencyProvider::PLUGINS_QUOTE_READER);
    }
}

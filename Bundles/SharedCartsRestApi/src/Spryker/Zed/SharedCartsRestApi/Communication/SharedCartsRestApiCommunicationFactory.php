<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCartsRestApi\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SharedCartsRestApi\Business\QuoteCollectionExpander\SharedCartQuoteCollectionExpander;
use Spryker\Zed\SharedCartsRestApi\Business\QuoteCollectionExpander\SharedCartQuoteCollectionExpanderInterface;
use Spryker\Zed\SharedCartsRestApi\Business\QuoteCollectionExpander\ShareDetailQuoteCollectionExpander;
use Spryker\Zed\SharedCartsRestApi\Business\QuoteCollectionExpander\ShareDetailQuoteCollectionExpanderInterface;
use Spryker\Zed\SharedCartsRestApi\Dependency\Facade\SharedCartsRestApiToSharedCartFacadeInterface;
use Spryker\Zed\SharedCartsRestApi\Dependency\Facade\SharedCartsRestApiToStoreFacadeInterface;
use Spryker\Zed\SharedCartsRestApi\SharedCartsRestApiDependencyProvider;

/**
 * @method \Spryker\Zed\SharedCartsRestApi\Business\SharedCartsRestApiFacade getFacade()
 * @method \Spryker\Zed\SharedCartsRestApi\SharedCartsRestApiConfig getConfig()
 */
class SharedCartsRestApiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\SharedCartsRestApi\Business\QuoteCollectionExpander\SharedCartQuoteCollectionExpanderInterface
     */
    public function createSharedCartQuoteCollectionExpander(): SharedCartQuoteCollectionExpanderInterface
    {
        return new SharedCartQuoteCollectionExpander(
            $this->getSharedCartFacade(),
            $this->getStoreFacade()
        );
    }

    /**
     * @return \Spryker\Zed\SharedCartsRestApi\Business\QuoteCollectionExpander\ShareDetailQuoteCollectionExpanderInterface
     */
    public function createShareDetailQuoteCollectionExpander(): ShareDetailQuoteCollectionExpanderInterface
    {
        return new ShareDetailQuoteCollectionExpander(
            $this->getSharedCartFacade()
        );
    }

    /**
     * @return \Spryker\Zed\SharedCartsRestApi\Dependency\Facade\SharedCartsRestApiToSharedCartFacadeInterface
     */
    public function getSharedCartFacade(): SharedCartsRestApiToSharedCartFacadeInterface
    {
        return $this->getProvidedDependency(SharedCartsRestApiDependencyProvider::FACADE_SHARED_CART);
    }

    /**
     * @return \Spryker\Zed\SharedCartsRestApi\Dependency\Facade\SharedCartsRestApiToStoreFacadeInterface
     */
    public function getStoreFacade(): SharedCartsRestApiToStoreFacadeInterface
    {
        return $this->getProvidedDependency(SharedCartsRestApiDependencyProvider::FACADE_STORE);
    }
}

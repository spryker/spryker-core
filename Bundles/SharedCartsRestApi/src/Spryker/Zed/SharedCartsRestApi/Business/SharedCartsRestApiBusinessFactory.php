<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCartsRestApi\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SharedCartsRestApi\Business\QuotePermissionGroup\QuotePermissionGroupReader;
use Spryker\Zed\SharedCartsRestApi\Business\QuotePermissionGroup\QuotePermissionGroupReaderInterface;
use Spryker\Zed\SharedCartsRestApi\Business\SharedCart\SharedCartCreator;
use Spryker\Zed\SharedCartsRestApi\Business\SharedCart\SharedCartCreatorInterface;
use Spryker\Zed\SharedCartsRestApi\Business\SharedCart\SharedCartDeleter;
use Spryker\Zed\SharedCartsRestApi\Business\SharedCart\SharedCartDeleterInterface;
use Spryker\Zed\SharedCartsRestApi\Business\SharedCart\SharedCartReader;
use Spryker\Zed\SharedCartsRestApi\Business\SharedCart\SharedCartReaderInterface;
use Spryker\Zed\SharedCartsRestApi\Business\SharedCart\SharedCartUpdater;
use Spryker\Zed\SharedCartsRestApi\Business\SharedCart\SharedCartUpdaterInterface;
use Spryker\Zed\SharedCartsRestApi\Dependency\Facade\SharedCartsRestApiToQuoteFacadeInterface;
use Spryker\Zed\SharedCartsRestApi\Dependency\Facade\SharedCartsRestApiToSharedCartFacadeInterface;
use Spryker\Zed\SharedCartsRestApi\SharedCartsRestApiDependencyProvider;

/**
 * @method \Spryker\Zed\SharedCartsRestApi\SharedCartsRestApiConfig getConfig()
 * @method \Spryker\Zed\SharedCartsRestApi\Persistence\SharedCartsRestApiEntityManager getEntityManager()
 */
class SharedCartsRestApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SharedCartsRestApi\Business\SharedCart\SharedCartReaderInterface
     */
    public function createSharedCartReader(): SharedCartReaderInterface
    {
        return new SharedCartReader(
            $this->getQuoteFacade(),
            $this->getSharedCartFacade()
        );
    }

    /**
     * @return \Spryker\Zed\SharedCartsRestApi\Business\SharedCart\SharedCartCreatorInterface
     */
    public function createSharedCartCreator(): SharedCartCreatorInterface
    {
        return new SharedCartCreator(
            $this->getQuoteFacade(),
            $this->getSharedCartFacade()
        );
    }

    /**
     * @return \Spryker\Zed\SharedCartsRestApi\Business\SharedCart\SharedCartUpdaterInterface
     */
    public function createSharedCartUpdater(): SharedCartUpdaterInterface
    {
        return new SharedCartUpdater($this->getSharedCartFacade());
    }

    /**
     * @return \Spryker\Zed\SharedCartsRestApi\Business\SharedCart\SharedCartDeleterInterface
     */
    public function createSharedCartDeleter(): SharedCartDeleterInterface
    {
        return new SharedCartDeleter($this->getSharedCartFacade());
    }

    /**
     * @return \Spryker\Zed\SharedCartsRestApi\Business\QuotePermissionGroup\QuotePermissionGroupReaderInterface
     */
    public function createQuotePermissionGroupReader(): QuotePermissionGroupReaderInterface
    {
        return new QuotePermissionGroupReader($this->getSharedCartFacade());
    }

    /**
     * @return \Spryker\Zed\SharedCartsRestApi\Dependency\Facade\SharedCartsRestApiToQuoteFacadeInterface
     */
    public function getQuoteFacade(): SharedCartsRestApiToQuoteFacadeInterface
    {
        return $this->getProvidedDependency(SharedCartsRestApiDependencyProvider::FACADE_QUOTE);
    }

    /**
     * @return \Spryker\Zed\SharedCartsRestApi\Dependency\Facade\SharedCartsRestApiToSharedCartFacadeInterface
     */
    public function getSharedCartFacade(): SharedCartsRestApiToSharedCartFacadeInterface
    {
        return $this->getProvidedDependency(SharedCartsRestApiDependencyProvider::FACADE_SHARED_CART);
    }
}

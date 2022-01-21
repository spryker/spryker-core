<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequestsRestApi\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\QuoteRequestsRestApi\Business\Creator\QuoteRequestCreator;
use Spryker\Zed\QuoteRequestsRestApi\Business\Creator\QuoteRequestCreatorInterface;
use Spryker\Zed\QuoteRequestsRestApi\Business\Mapper\QuoteRequestResponseMapper;
use Spryker\Zed\QuoteRequestsRestApi\Business\Mapper\QuoteRequestResponseMapperInterface;
use Spryker\Zed\QuoteRequestsRestApi\Business\Reader\QuoteReader;
use Spryker\Zed\QuoteRequestsRestApi\Business\Reader\QuoteReaderInterface;
use Spryker\Zed\QuoteRequestsRestApi\Business\Updater\QuoteRequestUpdater;
use Spryker\Zed\QuoteRequestsRestApi\Business\Updater\QuoteRequestUpdaterInterface;
use Spryker\Zed\QuoteRequestsRestApi\Dependency\Facade\QuoteRequestsRestApiToCartsRestApiFacadeInterface;
use Spryker\Zed\QuoteRequestsRestApi\Dependency\Facade\QuoteRequestsRestApiToQuoteRequestFacadeInterface;
use Spryker\Zed\QuoteRequestsRestApi\QuoteRequestsRestApiDependencyProvider;

/**
 * @method \Spryker\Zed\QuoteRequestsRestApi\QuoteRequestsRestApiConfig getConfig()
 */
class QuoteRequestsRestApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\QuoteRequestsRestApi\Business\Reader\QuoteReaderInterface
     */
    public function createQuoteReader(): QuoteReaderInterface
    {
        return new QuoteReader($this->getCartsRestApiFacade());
    }

    /**
     * @return \Spryker\Zed\QuoteRequestsRestApi\Business\Creator\QuoteRequestCreatorInterface
     */
    public function createQuoteRequestCreator(): QuoteRequestCreatorInterface
    {
        return new QuoteRequestCreator(
            $this->createQuoteReader(),
            $this->createQuoteRequestResponseMapper(),
            $this->getQuoteRequestFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\QuoteRequestsRestApi\Business\Updater\QuoteRequestUpdaterInterface
     */
    public function createQuoteRequestUpdater(): QuoteRequestUpdaterInterface
    {
        return new QuoteRequestUpdater(
            $this->createQuoteReader(),
            $this->createQuoteRequestResponseMapper(),
            $this->getQuoteRequestFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\QuoteRequestsRestApi\Business\Mapper\QuoteRequestResponseMapperInterface
     */
    public function createQuoteRequestResponseMapper(): QuoteRequestResponseMapperInterface
    {
        return new QuoteRequestResponseMapper();
    }

    /**
     * @return \Spryker\Zed\QuoteRequestsRestApi\Dependency\Facade\QuoteRequestsRestApiToCartsRestApiFacadeInterface
     */
    public function getCartsRestApiFacade(): QuoteRequestsRestApiToCartsRestApiFacadeInterface
    {
        return $this->getProvidedDependency(QuoteRequestsRestApiDependencyProvider::FACADE_CARTS_REST_API);
    }

    /**
     * @return \Spryker\Zed\QuoteRequestsRestApi\Dependency\Facade\QuoteRequestsRestApiToQuoteRequestFacadeInterface
     */
    public function getQuoteRequestFacade(): QuoteRequestsRestApiToQuoteRequestFacadeInterface
    {
        return $this->getProvidedDependency(QuoteRequestsRestApiDependencyProvider::FACADE_QUOTE_REQUEST);
    }
}

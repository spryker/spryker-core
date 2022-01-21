<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequestAgentsRestApi\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\QuoteRequestAgentsRestApi\Business\Reader\QuoteReader;
use Spryker\Zed\QuoteRequestAgentsRestApi\Business\Reader\QuoteReaderInterface;
use Spryker\Zed\QuoteRequestAgentsRestApi\Business\Updater\QuoteRequestUpdater;
use Spryker\Zed\QuoteRequestAgentsRestApi\Business\Updater\QuoteRequestUpdaterInterface;
use Spryker\Zed\QuoteRequestAgentsRestApi\Dependency\Facade\QuoteRequestAgentsRestApiToCartsRestApiFacadeInterface;
use Spryker\Zed\QuoteRequestAgentsRestApi\Dependency\Facade\QuoteRequestAgentsRestApiToQuoteRequestAgentFacadeInterface;
use Spryker\Zed\QuoteRequestAgentsRestApi\QuoteRequestAgentsRestApiDependencyProvider;

/**
 * @method \Spryker\Zed\QuoteRequestAgentsRestApi\QuoteRequestAgentsRestApiConfig getConfig()
 */
class QuoteRequestAgentsRestApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\QuoteRequestAgentsRestApi\Business\Reader\QuoteReaderInterface
     */
    public function createQuoteReader(): QuoteReaderInterface
    {
        return new QuoteReader($this->getCartsRestApiFacade());
    }

    /**
     * @return \Spryker\Zed\QuoteRequestAgentsRestApi\Business\Updater\QuoteRequestUpdaterInterface
     */
    public function createQuoteRequestUpdater(): QuoteRequestUpdaterInterface
    {
        return new QuoteRequestUpdater(
            $this->createQuoteReader(),
            $this->getQuoteRequestFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\QuoteRequestAgentsRestApi\Dependency\Facade\QuoteRequestAgentsRestApiToCartsRestApiFacadeInterface
     */
    public function getCartsRestApiFacade(): QuoteRequestAgentsRestApiToCartsRestApiFacadeInterface
    {
        return $this->getProvidedDependency(QuoteRequestAgentsRestApiDependencyProvider::FACADE_CARTS_REST_API);
    }

    /**
     * @return \Spryker\Zed\QuoteRequestAgentsRestApi\Dependency\Facade\QuoteRequestAgentsRestApiToQuoteRequestAgentFacadeInterface
     */
    public function getQuoteRequestFacade(): QuoteRequestAgentsRestApiToQuoteRequestAgentFacadeInterface
    {
        return $this->getProvidedDependency(QuoteRequestAgentsRestApiDependencyProvider::FACADE_QUOTE_REQUEST_AGENT);
    }
}

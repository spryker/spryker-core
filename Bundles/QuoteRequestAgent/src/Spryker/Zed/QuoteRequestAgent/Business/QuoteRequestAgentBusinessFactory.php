<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequestAgent\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\QuoteRequestAgent\Business\Reader\QuoteRequestReader;
use Spryker\Zed\QuoteRequestAgent\Business\Reader\QuoteRequestReaderInterface;
use Spryker\Zed\QuoteRequestAgent\Business\Writer\QuoteRequestWriter;
use Spryker\Zed\QuoteRequestAgent\Business\Writer\QuoteRequestWriterInterface;
use Spryker\Zed\QuoteRequestAgent\Dependency\Facade\QuoteRequestAgentToQuoteRequestFacadeInterface;
use Spryker\Zed\QuoteRequestAgent\QuoteRequestAgentDependencyProvider;

/**
 * @method \Spryker\Zed\QuoteRequestAgent\QuoteRequestAgentConfig getConfig()
 */
class QuoteRequestAgentBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\QuoteRequestAgent\Business\Reader\QuoteRequestReaderInterface
     */
    public function createQuoteRequestReader(): QuoteRequestReaderInterface
    {
        return new QuoteRequestReader(
            $this->getQuoteRequestFacade()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteRequestAgent\Business\Writer\QuoteRequestWriterInterface
     */
    public function createQuoteRequestWriter(): QuoteRequestWriterInterface
    {
        return new QuoteRequestWriter(
            $this->getQuoteRequestFacade()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteRequestAgent\Dependency\Facade\QuoteRequestAgentToQuoteRequestFacadeInterface
     */
    public function getQuoteRequestFacade(): QuoteRequestAgentToQuoteRequestFacadeInterface
    {
        return $this->getProvidedDependency(QuoteRequestAgentDependencyProvider::FACADE_QUOTE_REQUEST);
    }
}

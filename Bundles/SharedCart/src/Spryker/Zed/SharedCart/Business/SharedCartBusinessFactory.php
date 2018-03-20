<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SharedCart\Business\QuoteResponseExpander\QuoteResponseExpander;
use Spryker\Zed\SharedCart\Dependency\Facade\SharedCartToQuoteFacadeInterface;
use Spryker\Zed\SharedCart\SharedCartDependencyProvider;

/**
 * @method \Spryker\Zed\SharedCart\Persistence\SharedCartQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\SharedCart\SharedCartConfig getConfig()
 */
class SharedCartBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SharedCart\Business\QuoteResponseExpander\QuoteResponseExpanderInterface
     */
    public function createQuoteResponseExpander()
    {
        return new QuoteResponseExpander($this->getQuoteFacade());
    }

    /**
     * @return \Spryker\Zed\SharedCart\Dependency\Facade\SharedCartToQuoteFacadeInterface
     */
    public function getQuoteFacade(): SharedCartToQuoteFacadeInterface
    {
        return $this->getProvidedDependency(SharedCartDependencyProvider::FACADE_QUOTE);
    }
}

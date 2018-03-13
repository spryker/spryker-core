<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiCart\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MultiCart\Business\Model\QuoteReader;
use Spryker\Zed\MultiCart\Business\Model\QuoteReaderInterface;
use Spryker\Zed\MultiCart\MultiCartDependencyProvider;

class MultiCartBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MultiCart\Business\Model\QuoteReaderInterface
     */
    public function createQuoteReader(): QuoteReaderInterface
    {
        return new QuoteReader(
            $this->getQuoteFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToQuoteFacadeInterface
     */
    protected function getQuoteFacade()
    {
        return $this->getProvidedDependency(MultiCartDependencyProvider::FACADE_QUOTE);
    }

    /**
     * @return \Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToPersistentCartFacadeInterface
     */
    protected function getPersistentCartFacade()
    {
        return $this->getProvidedDependency(MultiCartDependencyProvider::FACADE_PERSISTENT_CART);
    }
}

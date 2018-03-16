<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiCart\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MultiCart\Business\Model\QuoteActivator;
use Spryker\Zed\MultiCart\Business\Model\QuoteActivatorInterface;
use Spryker\Zed\MultiCart\Business\Model\QuoteResponseExpander;
use Spryker\Zed\MultiCart\Business\Model\QuoteResponseExpanderInterface;
use Spryker\Zed\MultiCart\MultiCartDependencyProvider;

class MultiCartBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MultiCart\Business\Model\QuoteActivatorInterface
     */
    public function createQuoteActivator(): QuoteActivatorInterface
    {
        return new QuoteActivator(
            $this->getQuoteFacade(),
            $this->getPersistentCartFacade(),
            $this->getMessengerFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MultiCart\Business\Model\QuoteResponseExpanderInterface
     */
    public function createQuoteResponseExpander(): QuoteResponseExpanderInterface
    {
        return new QuoteResponseExpander($this->getQuoteFacade());
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

    /**
     * @return \Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToMessengerFacadeInterface
     */
    protected function getMessengerFacade()
    {
        return $this->getProvidedDependency(MultiCartDependencyProvider::FACADE_MESSENGER);
    }
}

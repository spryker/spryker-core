<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartNote\Business;

use Spryker\Zed\CartNote\Business\Model\CartNoteSaver;
use Spryker\Zed\CartNote\Business\Model\CartNoteSaverInterface;
use Spryker\Zed\CartNote\Business\Model\QuoteCartNoteSetter;
use Spryker\Zed\CartNote\Business\Model\QuoteCartNoteSetterInterface;
use Spryker\Zed\CartNote\CartNoteDependencyProvider;
use Spryker\Zed\CartNoteExtension\Dependency\Plugin\QuoteItemFinderPluginInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CartNote\Persistence\CartNoteEntityManagerInterface getEntityManager()
 */
class CartNoteBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CartNote\Business\Model\CartNoteSaverInterface
     */
    public function createCartNoteSaver(): CartNoteSaverInterface
    {
        return new CartNoteSaver($this->getEntityManager());
    }

    /**
     * @return \Spryker\Zed\CartNote\Business\Model\QuoteCartNoteSetterInterface
     */
    public function createQuoteCartNoteSetter(): QuoteCartNoteSetterInterface
    {
        return new QuoteCartNoteSetter($this->getQuoteFacade(), $this->getQuoteItemsFinderPlugin());
    }

    /**
     * @return \Spryker\Zed\CartNote\Dependency\Facade\CartNoteToQuoteFacadeInterface
     */
    public function getQuoteFacade()
    {
        return $this->getProvidedDependency(CartNoteDependencyProvider::FACADE_QUOTE);
    }

    /**
     * @return \Spryker\Zed\CartNoteExtension\Dependency\Plugin\QuoteItemFinderPluginInterface
     */
    protected function getQuoteItemsFinderPlugin(): QuoteItemFinderPluginInterface
    {
        return $this->getProvidedDependency(CartNoteDependencyProvider::PLUGIN_QUOTE_ITEMS_FINDER);
    }
}

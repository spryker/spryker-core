<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Quote\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Quote\Business\QuoteFacadeInterface;
use SprykerTest\Shared\Testify\Helper\DependencyHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class PersistentQuoteHelper extends Module
{
    use DependencyHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param array $seed
     * @param bool|null $shouldReloadItems
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function havePersistentQuote(array $seed = [], ?bool $shouldReloadItems = false): QuoteTransfer
    {
        $quoteTransfer = (new QuoteBuilder($seed))->build();
        $quoteTransfer->setIdQuote(null);

        $quoteTransfer->requireCustomer();

        $this->assureCurrency($quoteTransfer);
        $this->assureStore($quoteTransfer);

        $quoteResponseTransfer = $this->getFacade()->createQuote($quoteTransfer);

        if ($shouldReloadItems) {
            $quoteTransfer = $quoteResponseTransfer->getQuoteTransfer();
            $quoteTransfer = $this->getLocator()->cart()->facade()->reloadItems($quoteTransfer);
            $quoteResponseTransfer = $this->getFacade()->updateQuote($quoteTransfer);
        }

        return $quoteResponseTransfer->getQuoteTransfer();
    }

    /**
     * @return \Spryker\Zed\Quote\Business\QuoteFacadeInterface
     */
    private function getFacade(): QuoteFacadeInterface
    {
        return $this->getLocator()->quote()->facade();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function assureCurrency(QuoteTransfer $quoteTransfer): void
    {
        if (!$quoteTransfer->getCurrency()) {
            $currencyTransfer = $this->getLocator()
                ->currency()
                ->facade()
                ->fromIsoCode('EUR');

            $quoteTransfer->setCurrency($currencyTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function assureStore(QuoteTransfer $quoteTransfer): void
    {
        if (!$quoteTransfer->getStore()) {
            $storeTransfer = $this->getLocator()
                ->store()
                ->facade()
                ->getStoreByName('DE')
                ->setDefaultLocaleIsoCode('en_US')
                ->setDefaultCurrencyIsoCode('EUR');

            $quoteTransfer->setStore($storeTransfer);
        }
    }
}

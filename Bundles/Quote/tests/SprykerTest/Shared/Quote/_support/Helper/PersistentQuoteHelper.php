<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Quote\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerTest\Shared\Testify\Helper\DependencyHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class PersistentQuoteHelper extends Module
{
    use DependencyHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function havePersistentQuote(array $seed = [])
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = (new QuoteBuilder($seed))->build();
        $quoteTransfer->setIdQuote(null);

        $quoteTransfer->requireCustomer();

        $this->assureCurrency($quoteTransfer);
        $this->assureStore($quoteTransfer);
        $this->assureUuid($quoteTransfer);

        $quoteResponseTransfer = $this->getFacade()->createQuote($quoteTransfer);

        return $quoteResponseTransfer->getQuoteTransfer();
    }

    /**
     * @return \Spryker\Zed\Quote\Business\QuoteFacadeInterface
     */
    private function getFacade()
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
                ->getCurrent();

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
                ->getStoreByName('DE');

            $quoteTransfer->setStore($storeTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function assureUuid(QuoteTransfer $quoteTransfer): void
    {
        if (!$quoteTransfer->getUuid()) {
            $quoteTransfer->setUuid('test-uuid-1');
        }
    }
}

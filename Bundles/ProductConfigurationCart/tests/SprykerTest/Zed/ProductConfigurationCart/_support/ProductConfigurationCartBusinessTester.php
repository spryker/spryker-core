<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductConfigurationCart;

use Codeception\Actor;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 * @method \Spryker\Zed\ProductConfigurationCart\Business\ProductConfigurationCartFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductConfigurationCartBusinessTester extends Actor
{
    use _generated\ProductConfigurationCartBusinessTesterActions;

    protected const PRODUCT_CONFIGURATION_TEST_KEY = 'product_configuration_test_key';

    /**
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    public function haveQuoteRequestInDraftStatusWithIncompleteConfiguredProduct(): QuoteRequestTransfer
    {
        $quoteRequestTransfer = $this->createQuoteRequestTransfer();

        foreach ($quoteRequestTransfer->getLatestVersion()->getQuote()->getItems() as $itemTransfer) {
            $itemTransfer->setProductConfigurationInstance($this->createProductConfigurationInstance(false));
        }

        return $quoteRequestTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    public function haveQuoteRequestInDraftStatusWithCompleteConfiguredProduct(): QuoteRequestTransfer
    {
        $quoteRequestTransfer = $this->createQuoteRequestTransfer();

        foreach ($quoteRequestTransfer->getLatestVersion()->getQuote()->getItems() as $itemTransfer) {
            $itemTransfer->setProductConfigurationInstance($this->createProductConfigurationInstance());
        }

        return $quoteRequestTransfer;
    }

    /**
     * @param bool $isConfigurationComplete
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer
     */
    public function createProductConfigurationInstance(bool $isConfigurationComplete = true): ProductConfigurationInstanceTransfer
    {
        return (new ProductConfigurationInstanceTransfer())
            ->setConfiguratorKey(static::PRODUCT_CONFIGURATION_TEST_KEY)
            ->setIsComplete($isConfigurationComplete);
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    public function createQuoteRequestTransfer(): QuoteRequestTransfer
    {
        $quoteTransfer = $this->createQuoteTransfer();
        $quoteRequestVersionTransfer = $this->createQuoteRequestVersionTransfer($quoteTransfer);

        return (new QuoteRequestTransfer())->setLatestVersion($quoteRequestVersionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionTransfer
     */
    public function createQuoteRequestVersionTransfer(QuoteTransfer $quoteTransfer): QuoteRequestVersionTransfer
    {
        return $this->haveQuoteRequestVersion([
            QuoteRequestVersionTransfer::QUOTE => $quoteTransfer,
        ]);
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransfer(): QuoteTransfer
    {
        return (new QuoteBuilder())
            ->withItem([
                ItemTransfer::SKU => $this->haveProduct()->getSku(),
                ItemTransfer::UNIT_PRICE => 100,
                ItemTransfer::QUANTITY => 1,
            ])
            ->withAnotherItem([
                ItemTransfer::SKU => $this->haveProduct()->getSku(),
                ItemTransfer::UNIT_PRICE => 25,
                ItemTransfer::QUANTITY => 5,
            ])
            ->build();
    }
}

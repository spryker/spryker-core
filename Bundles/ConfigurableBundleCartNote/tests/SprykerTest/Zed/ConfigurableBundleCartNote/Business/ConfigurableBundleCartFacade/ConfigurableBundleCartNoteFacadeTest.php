<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ConfigurableBundleCartNote\Business\ConfigurableBundleCartNoteFacade;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\ConfiguredBundleBuilder;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\DataBuilder\ProductConcreteBuilder;
use Generated\Shared\Transfer\ConfiguredBundleCartNoteRequestTransfer;
use Generated\Shared\Transfer\ConfiguredBundleTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ConfigurableBundleCartNote
 * @group Business
 * @group ConfigurableBundleCartNoteFacade
 * @group Facade
 * @group ConfigurableBundleCartNoteFacadeTest
 * Add your own group annotations below this line
 */
class ConfigurableBundleCartNoteFacadeTest extends Test
{
    protected const FAKE_CUSTOMER_REFERENCE = 'FAKE_CUSTOMER_REFERENCE';
    protected const FAKE_CONFIGURABLE_BUNDLE_CART_NOTE = 'Configurable Bundle Cart Note';
    protected const FAKE_CONFIGURABLE_BUNDLE_GROUP_KEY = 'configurable-bundle-group-key';

    /**
     * @var \SprykerTest\Zed\ConfigurableBundleCartNote\ConfigurableBundleCartNoteBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testSetCartNoteToConfigurableBundleReturnsSuccessfulQuoteResponseTransfer(): void
    {
        //Arrange
        $configuredBundleCartNoteRequestTransfer = (new ConfiguredBundleCartNoteRequestTransfer())
            ->setQuote($this->createFakeQuoteTransferWithConfiguredBundle())
            ->setGroupKey(static::FAKE_CONFIGURABLE_BUNDLE_GROUP_KEY)
            ->setCartNote(static::FAKE_CONFIGURABLE_BUNDLE_CART_NOTE);

        //Act
        $quoteResponseTransfer = $this->tester->getFacade()->setCartNoteToConfigurableBundle($configuredBundleCartNoteRequestTransfer);

        //Assert
        $quoteTransfer = $quoteResponseTransfer->getQuoteTransfer();
        $this->assertInstanceOf(QuoteTransfer::class, $quoteTransfer);
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $this->assertSame(static::FAKE_CONFIGURABLE_BUNDLE_CART_NOTE, $itemTransfer->getConfiguredBundle()->getCartNote());
        }
    }

    /**
     * @return void
     */
    public function testSetCartNoteToConfigurableBundleWithNotExistingConfigurableBundleGroupKey(): void
    {
        //Arrange
        $configuredBundleCartNoteRequestTransfer = (new ConfiguredBundleCartNoteRequestTransfer())
            ->setQuote($this->createFakeQuoteTransferWithConfiguredBundle())
            ->setGroupKey('not-existing-configurable-bundle-group-key')
            ->setCartNote(static::FAKE_CONFIGURABLE_BUNDLE_CART_NOTE);

        //Act
        $quoteResponseTransfer = $this->tester->getFacade()->setCartNoteToConfigurableBundle($configuredBundleCartNoteRequestTransfer);

        //Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testSetCartNoteToConfigurableBundleWithoutConfigurableBundles(): void
    {
        //Arrange
        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $this->createCustomerTransfer(static::FAKE_CUSTOMER_REFERENCE),
        ]);
        $configuredBundleCartNoteRequestTransfer = (new ConfiguredBundleCartNoteRequestTransfer())
            ->setQuote($quoteTransfer)
            ->setGroupKey(static::FAKE_CONFIGURABLE_BUNDLE_GROUP_KEY)
            ->setCartNote(static::FAKE_CONFIGURABLE_BUNDLE_CART_NOTE);

        //Act
        $quoteResponseTransfer = $this->tester->getFacade()->setCartNoteToConfigurableBundle($configuredBundleCartNoteRequestTransfer);

        //Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createFakeQuoteTransferWithConfiguredBundle(): QuoteTransfer
    {
        return $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $this->createCustomerTransfer(static::FAKE_CUSTOMER_REFERENCE),
            QuoteTransfer::ITEMS => [
                [
                    ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                    ItemTransfer::UNIT_PRICE => 1,
                    ItemTransfer::QUANTITY => 1,
                    ItemTransfer::CONFIGURED_BUNDLE => $this->createConfiguredBundleTransfer(static::FAKE_CONFIGURABLE_BUNDLE_GROUP_KEY),
                ],
                [
                    ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                    ItemTransfer::UNIT_PRICE => 1,
                    ItemTransfer::QUANTITY => 1,
                    ItemTransfer::CONFIGURED_BUNDLE => $this->createConfiguredBundleTransfer(static::FAKE_CONFIGURABLE_BUNDLE_GROUP_KEY),
                ],
            ],
        ]);
    }

    /**
     * @param string|null $customerReference
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function createCustomerTransfer(?string $customerReference = null): CustomerTransfer
    {
        return (new CustomerBuilder())->build()
            ->setCustomerReference($customerReference);
    }

    /**
     * @param string|null $groupKey
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundleTransfer
     */
    protected function createConfiguredBundleTransfer(?string $groupKey = null): ConfiguredBundleTransfer
    {
        return (new ConfiguredBundleBuilder())->build()
            ->setGroupKey($groupKey);
    }
}

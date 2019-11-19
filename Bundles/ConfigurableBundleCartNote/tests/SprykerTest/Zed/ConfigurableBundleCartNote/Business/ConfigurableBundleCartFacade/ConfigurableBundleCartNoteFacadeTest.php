<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ConfigurableBundleCartNote\Business\ConfigurableBundleCartNoteFacade;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ConfiguredBundleCartNoteRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerTest\Zed\ConfigurableBundleCartNote\ConfigurableBundleCartNoteBusinessTester;

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
            ->setQuote($this->tester->createQuoteTransferWithConfiguredBundle())
            ->setGroupKey(ConfigurableBundleCartNoteBusinessTester::FAKE_CONFIGURABLE_BUNDLE_GROUP_KEY)
            ->setCartNote(ConfigurableBundleCartNoteBusinessTester::FAKE_CONFIGURABLE_BUNDLE_CART_NOTE);

        //Act
        $quoteResponseTransfer = $this->tester->getFacade()->setCartNoteToConfiguredBundle($configuredBundleCartNoteRequestTransfer);

        //Assert
        $quoteTransfer = $quoteResponseTransfer->getQuoteTransfer();
        $this->assertInstanceOf(QuoteTransfer::class, $quoteTransfer);
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $this->assertSame(
                ConfigurableBundleCartNoteBusinessTester::FAKE_CONFIGURABLE_BUNDLE_CART_NOTE,
                $itemTransfer->getConfiguredBundle()->getCartNote()
            );
        }
    }

    /**
     * @return void
     */
    public function testSetCartNoteToConfigurableBundleWithNotExistingConfigurableBundleGroupKey(): void
    {
        //Arrange
        $configuredBundleCartNoteRequestTransfer = (new ConfiguredBundleCartNoteRequestTransfer())
            ->setQuote($this->tester->createQuoteTransferWithConfiguredBundle())
            ->setGroupKey('not-existing-configurable-bundle-group-key')
            ->setCartNote(ConfigurableBundleCartNoteBusinessTester::FAKE_CONFIGURABLE_BUNDLE_CART_NOTE);

        //Act
        $quoteResponseTransfer = $this->tester->getFacade()->setCartNoteToConfiguredBundle($configuredBundleCartNoteRequestTransfer);

        //Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testSetCartNoteToConfigurableBundleWithoutConfigurableBundles(): void
    {
        //Arrange
        $configuredBundleCartNoteRequestTransfer = (new ConfiguredBundleCartNoteRequestTransfer())
            ->setQuote($this->tester->createQuoteTransfer())
            ->setGroupKey(ConfigurableBundleCartNoteBusinessTester::FAKE_CONFIGURABLE_BUNDLE_GROUP_KEY)
            ->setCartNote(ConfigurableBundleCartNoteBusinessTester::FAKE_CONFIGURABLE_BUNDLE_CART_NOTE);

        //Act
        $quoteResponseTransfer = $this->tester->getFacade()->setCartNoteToConfiguredBundle($configuredBundleCartNoteRequestTransfer);

        //Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
    }
}

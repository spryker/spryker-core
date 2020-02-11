<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ConfigurableBundleNote\Business\ConfigurableBundleNoteFacade;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ConfiguredBundleNoteRequestTransfer;
use Generated\Shared\Transfer\ConfiguredBundleTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerTest\Zed\ConfigurableBundleNote\ConfigurableBundleNoteBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ConfigurableBundleNote
 * @group Business
 * @group ConfigurableBundleNoteFacade
 * @group Facade
 * @group ConfigurableBundleNoteFacadeTest
 * Add your own group annotations below this line
 */
class ConfigurableBundleNoteFacadeTest extends Test
{
    /**
     * @var \SprykerTest\Zed\ConfigurableBundleNote\ConfigurableBundleNoteBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testSetNoteToConfigurableBundleReturnsSuccessfulQuoteResponseTransfer(): void
    {
        //Arrange
        $configuredBundleNoteRequestTransfer = (new ConfiguredBundleNoteRequestTransfer())
            ->setQuote($this->tester->createQuoteTransferWithConfiguredBundle())
            ->setConfiguredBundle(
                (new ConfiguredBundleTransfer())
                    ->setGroupKey(ConfigurableBundleNoteBusinessTester::FAKE_CONFIGURABLE_BUNDLE_GROUP_KEY)
                    ->setNote(ConfigurableBundleNoteBusinessTester::FAKE_CONFIGURABLE_BUNDLE_NOTE)
            );

        //Act
        $quoteResponseTransfer = $this->tester->getFacade()->setConfiguredBundleNote($configuredBundleNoteRequestTransfer);

        //Assert
        $quoteTransfer = $quoteResponseTransfer->getQuoteTransfer();
        $this->assertInstanceOf(QuoteTransfer::class, $quoteTransfer);
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $this->assertSame(
                ConfigurableBundleNoteBusinessTester::FAKE_CONFIGURABLE_BUNDLE_NOTE,
                $itemTransfer->getConfiguredBundle()->getNote()
            );
        }
    }

    /**
     * @return void
     */
    public function testSetNoteToConfigurableBundleWithNotExistingConfigurableBundleGroupKey(): void
    {
        //Arrange
        $configuredBundleNoteRequestTransfer = (new ConfiguredBundleNoteRequestTransfer())
            ->setQuote($this->tester->createQuoteTransferWithConfiguredBundle())
            ->setConfiguredBundle(
                (new ConfiguredBundleTransfer())
                    ->setGroupKey('not-existing-configurable-bundle-group-key')
                    ->setNote(ConfigurableBundleNoteBusinessTester::FAKE_CONFIGURABLE_BUNDLE_NOTE)
            );

        //Act
        $quoteResponseTransfer = $this->tester->getFacade()->setConfiguredBundleNote($configuredBundleNoteRequestTransfer);

        //Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testSetNoteToConfigurableBundleWithoutConfigurableBundles(): void
    {
        //Arrange
        $configuredBundleNoteRequestTransfer = (new ConfiguredBundleNoteRequestTransfer())
            ->setQuote($this->tester->createQuoteTransfer())
            ->setConfiguredBundle(
                (new ConfiguredBundleTransfer())
                    ->setGroupKey(ConfigurableBundleNoteBusinessTester::FAKE_CONFIGURABLE_BUNDLE_GROUP_KEY)
                    ->setNote(ConfigurableBundleNoteBusinessTester::FAKE_CONFIGURABLE_BUNDLE_NOTE)
            );

        //Act
        $quoteResponseTransfer = $this->tester->getFacade()->setConfiguredBundleNote($configuredBundleNoteRequestTransfer);

        //Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
    }
}

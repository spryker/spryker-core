<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SalesOrderAmendment\Plugin\Price;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\SalesOrderAmendment\Dependency\Client\SalesOrderAmendmentToMessengerClientInterface;
use Spryker\Client\SalesOrderAmendment\Plugin\Price\SalesOrderAmendmentCurrentPriceModePreCheckPlugin;
use Spryker\Client\SalesOrderAmendment\SalesOrderAmendmentDependencyProvider;
use SprykerTest\Client\SalesOrderAmendment\SalesOrderAmendmentClientTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group SalesOrderAmendment
 * @group Plugin
 * @group Price
 * @group SalesOrderAmendmentCurrentPriceModePreCheckPluginTest
 * Add your own group annotations below this line
 */
class SalesOrderAmendmentCurrentPriceModePreCheckPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Client\SalesOrderAmendment\SalesOrderAmendmentClientTester
     */
    protected SalesOrderAmendmentClientTester $tester;

    /**
     * @return void
     */
    public function testReturnsTrueWhenAmendmentOrderReferenceIsNotSetAndPriceModeHasBeenChanged(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())->setPriceMode('test_mode_1');

        // Act
        $isValid = (new SalesOrderAmendmentCurrentPriceModePreCheckPlugin())->isPriceModeChangeAllowed('test_mode_2', $quoteTransfer);

        // Assert
        $this->assertTrue($isValid);
    }

    /**
     * @return void
     */
    public function testReturnsTrueWhenAmendmentOrderReferenceIsSetAndPriceModeHasNotBeenChanged(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())
            ->setAmendmentOrderReference('test_reference')
            ->setPriceMode('test_mode_1');

        // Act
        $isValid = (new SalesOrderAmendmentCurrentPriceModePreCheckPlugin())->isPriceModeChangeAllowed('test_mode_1', $quoteTransfer);

        // Assert
        $this->assertTrue($isValid);
    }

    /**
     * @return void
     */
    public function testReturnsFalseWhenAmendmentOrderReferenceIsSetAndPriceModeHasBeenChanged(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())
            ->setAmendmentOrderReference('test_reference')
            ->setPriceMode('test_mode_1');
        $this->tester->setDependency(
            SalesOrderAmendmentDependencyProvider::CLIENT_MESSENGER,
            $this->createMock(SalesOrderAmendmentToMessengerClientInterface::class),
        );

        // Act
        $isValid = (new SalesOrderAmendmentCurrentPriceModePreCheckPlugin())->isPriceModeChangeAllowed('test_mode_2', $quoteTransfer);

        // Assert
        $this->assertFalse($isValid);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SalesOrderAmendment\Plugin\Currency;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\SalesOrderAmendment\Dependency\Client\SalesOrderAmendmentToQuoteClientInterface;
use Spryker\Client\SalesOrderAmendment\Plugin\Currency\SalesOrderAmendmentCurrentCurrencyIsoCodePreCheckPlugin;
use Spryker\Client\SalesOrderAmendment\SalesOrderAmendmentFactory;
use SprykerTest\Client\SalesOrderAmendment\SalesOrderAmendmentClientTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group SalesOrderAmendment
 * @group Plugin
 * @group Currency
 * @group SalesOrderAmendmentCurrentCurrencyIsoCodePreCheckPluginTest
 * Add your own group annotations below this line
 */
class SalesOrderAmendmentCurrentCurrencyIsoCodePreCheckPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Client\SalesOrderAmendment\SalesOrderAmendmentClientTester
     */
    protected SalesOrderAmendmentClientTester $tester;

    /**
     * @return void
     */
    public function testReturnsTrueWhenAmendmentOrderReferenceIsNotSetAndCurrencyHasBeenChangedToQuote(): void
    {
        // Arrange
        $currencyTransfer1 = (new CurrencyTransfer())->setCode('test_code_1');
        $currencyTransfer2 = (new CurrencyTransfer())->setCode('test_code_2');
        $quoteTransfer = (new QuoteTransfer())->setCurrency($currencyTransfer1);
        $salesOrderAmendmentCurrencyChangeValidatorPlugin = (new SalesOrderAmendmentCurrentCurrencyIsoCodePreCheckPlugin())
            ->setFactory($this->getSalesOrderAmendmentFactoryMock($quoteTransfer));

        // Act
        $isValid = $salesOrderAmendmentCurrencyChangeValidatorPlugin->isCurrencyChangeAllowed($currencyTransfer2);

        // Assert
        $this->assertTrue($isValid);
    }

    /**
     * @return void
     */
    public function testReturnsTrueWhenAmendmentOrderReferenceIsSetAndCurrencyHasNotBeenChangedToQuote(): void
    {
        // Arrange
        $currencyTransfer = (new CurrencyTransfer())->setCode('test_code');
        $quoteTransfer = (new QuoteTransfer())->setCurrency($currencyTransfer)->setAmendmentOrderReference('test_reference');
        $salesOrderAmendmentCurrencyChangeValidatorPlugin = (new SalesOrderAmendmentCurrentCurrencyIsoCodePreCheckPlugin())
            ->setFactory($this->getSalesOrderAmendmentFactoryMock($quoteTransfer));

        // Act
        $isValid = $salesOrderAmendmentCurrencyChangeValidatorPlugin->isCurrencyChangeAllowed($currencyTransfer);

        // Assert
        $this->assertTrue($isValid);
    }

    /**
     * @return void
     */
    public function testReturnsFalseWhenAmendmentOrderReferenceIsSetAndCurrencyHasBeenChangedToQuote(): void
    {
        // Arrange
        $currencyTransfer1 = (new CurrencyTransfer())->setCode('test_code_1');
        $currencyTransfer2 = (new CurrencyTransfer())->setCode('test_code_2');
        $quoteTransfer = (new QuoteTransfer())->setCurrency($currencyTransfer1)->setAmendmentOrderReference('test_reference');
        $salesOrderAmendmentCurrencyChangeValidatorPlugin = (new SalesOrderAmendmentCurrentCurrencyIsoCodePreCheckPlugin())
            ->setFactory($this->getSalesOrderAmendmentFactoryMock($quoteTransfer));

        // Act
        $isValid = $salesOrderAmendmentCurrencyChangeValidatorPlugin->isCurrencyChangeAllowed($currencyTransfer2);

        // Assert
        $this->assertFalse($isValid);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Client\SalesOrderAmendment\SalesOrderAmendmentFactory
     */
    protected function getSalesOrderAmendmentFactoryMock(QuoteTransfer $quoteTransfer): SalesOrderAmendmentFactory
    {
        $factoryMock = $this->getMockBuilder(SalesOrderAmendmentFactory::class)
            ->onlyMethods(['getQuoteClient', 'getMessengerClient'])
            ->getMock();
        $factoryMock->method('getQuoteClient')->willReturn($this->getQuoteClientMock($quoteTransfer));

        return $factoryMock;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Client\SalesOrderAmendment\Dependency\Client\SalesOrderAmendmentToQuoteClientInterface
     */
    protected function getQuoteClientMock(QuoteTransfer $quoteTransfer): SalesOrderAmendmentToQuoteClientInterface
    {
        $quoteClientMock = $this->getMockBuilder(SalesOrderAmendmentToQuoteClientInterface::class)->getMock();
        $quoteClientMock->method('getQuote')->willReturn($quoteTransfer);

        return $quoteClientMock;
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Money\Parser;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CurrencyTransfer;
use Money\Currencies\ISOCurrencies;
use Money\Parser\IntlMoneyParser;
use NumberFormatter;
use Spryker\Shared\Money\Dependency\Parser\MoneyToParserBridge;
use Spryker\Shared\Money\Mapper\MoneyToTransferMapper;
use Spryker\Shared\Money\Parser\Parser;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Money
 * @group Parser
 * @group ParserTest
 * Add your own group annotations below this line
 */
class ParserTest extends Unit
{
    /**
     * @dataProvider parseData
     *
     * @param string $value
     * @param string $locale
     * @param string $isoCode
     * @param string $expectedAmount
     *
     * @return void
     */
    public function testParseShouldReturnMoneyTransfer($value, $locale, $isoCode, $expectedAmount)
    {
        $parser = $this->getParser($locale);
        $currencyTransfer = new CurrencyTransfer();
        $currencyTransfer->setCode($isoCode);

        $moneyTransfer = $parser->parse($value, $currencyTransfer);

        $this->assertSame($expectedAmount, $moneyTransfer->getAmount());
    }

    /**
     * @return array
     */
    public function parseData()
    {
        return [
            ['10,00 €', 'de_DE', 'EUR', '1000'],
            ['10,99 €', 'de_DE', 'EUR', '1099'],
            ['1000 ¥', 'de_DE', 'JPY', '1000'],
            ['1099 ¥', 'de_DE', 'JPY', '1099'],
        ];
    }

    /**
     * @param string $locale
     *
     * @return \Spryker\Shared\Money\Parser\Parser
     */
    protected function getParser($locale)
    {
        $numberFormatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);
        $intlMoneyParser = new IntlMoneyParser($numberFormatter, new ISOCurrencies());

        $moneyToParserBridge = new MoneyToParserBridge($intlMoneyParser);

        $moneyToTransferMapper = new MoneyToTransferMapper();
        $parser = new Parser($moneyToParserBridge, $moneyToTransferMapper);

        return $parser;
    }
}

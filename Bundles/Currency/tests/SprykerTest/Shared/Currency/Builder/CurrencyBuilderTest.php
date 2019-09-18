<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Currency\Builder;

use Codeception\Test\Unit;
use Spryker\Shared\Currency\Builder\CurrencyBuilder;
use Spryker\Shared\Currency\Builder\CurrencyBuilderInterface;
use Spryker\Shared\Currency\Dependency\Internationalization\CurrencyToInternationalizationBridge;
use Spryker\Shared\Kernel\Store;
use Symfony\Component\Intl\Intl;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Currency
 * @group Builder
 * @group CurrencyBuilderTest
 * Add your own group annotations below this line
 */
class CurrencyBuilderTest extends Unit
{
    public const DEFAULT_CURRENCY = 'EUR';

    /**
     * @return void
     */
    public function testConstruct()
    {
        $currencyBuilder = $this->getCurrencyBuilder();
        $this->assertInstanceOf(CurrencyBuilderInterface::class, $currencyBuilder);
    }

    /**
     * @return void
     */
    public function testFromIsoCodeShouldReturnCurrencyTransfer()
    {
        $currencyBuilder = $this->getCurrencyBuilder();

        $currencyTransfer = $currencyBuilder->fromIsoCode(self::DEFAULT_CURRENCY);
        $this->assertSame(self::DEFAULT_CURRENCY, $currencyTransfer->getCode());
    }

    /**
     * @return \Spryker\Shared\Currency\Builder\CurrencyBuilderInterface
     */
    protected function getCurrencyBuilder()
    {
        $currencyRepository = new CurrencyToInternationalizationBridge(Intl::getCurrencyBundle());

        return new CurrencyBuilder(
            $currencyRepository,
            'EUR',
            Store::getInstance()->getCurrencyIsoCode()
        );
    }
}

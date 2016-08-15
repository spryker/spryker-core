<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Shared\Currency\Builder;

use Spryker\Shared\Currency\Builder\CurrencyBuilder;
use Spryker\Shared\Currency\Builder\CurrencyBuilderInterface;
use Spryker\Shared\Kernel\Store;
use Symfony\Component\Intl\Intl;

/**
 * @group Unit
 * @group Spryker
 * @group Shared
 * @group Currency
 * @group Builder
 * @group CurrencyBuilderTest
 */
class CurrencyBuilderTest extends \PHPUnit_Framework_TestCase
{

    const DEFAULT_CURRENCY = 'EUR';

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
        return new CurrencyBuilder(
            Intl::getCurrencyBundle(),
            Store::getInstance()->getCurrencyIsoCode()
        );
    }

}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\PriceProduct;

use Codeception\Actor;
use Generated\Shared\DataBuilder\CurrencyBuilder;
use Generated\Shared\DataBuilder\MoneyValueBuilder;
use Generated\Shared\DataBuilder\PriceProductBuilder;
use Generated\Shared\Transfer\PriceProductTransfer;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class PriceProductTester extends Actor
{
    use _generated\PriceProductTesterActions;

    /**
     * @param array<string, mixed> $priceProductSeed
     * @param array<string, mixed> $moneyValueSeed
     * @param array<string, mixed> $currencySeed
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function createPriceProductTransfer(
        array $priceProductSeed,
        array $moneyValueSeed = [],
        array $currencySeed = []
    ): PriceProductTransfer {
        $priceProductBuilder = new PriceProductBuilder($priceProductSeed);
        $currencyBuilder = new CurrencyBuilder($currencySeed);
        $moneyValueBuilder = (new MoneyValueBuilder($moneyValueSeed))->withCurrency($currencyBuilder);
        $priceProductBuilder->withMoneyValue($moneyValueBuilder);

        return $priceProductBuilder->build();
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Money;

use Codeception\Actor;
use ReflectionClass;
use Spryker\Shared\Money\Formatter\IntlMoneyFormatter\IntlMoneyFormatterWithoutCurrency;

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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class MoneyYvesTester extends Actor
{
    use _generated\MoneyYvesTesterActions;

    /**
     * @return void
     */
    public function clearLocaleCacheForMoneyFormatter(): void
    {
        $moneyFormatter = new ReflectionClass(IntlMoneyFormatterWithoutCurrency::class);
        $localeProperty = $moneyFormatter->getProperty('locale');
        $localeProperty->setAccessible(true);
        $localeProperty->setValue(null);
    }
}

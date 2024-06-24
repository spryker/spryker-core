<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantCommission;

use Codeception\Actor;
use Spryker\Shared\Kernel\Store;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
 */
class MerchantCommissionCommunicationTester extends Actor
{
    use _generated\MerchantCommissionCommunicationTesterActions;

    /**
     * @var string
     */
    protected const LOCALE_EN_US = 'en_US';

    /**
     * @return void
     */
    public function setStoreLocale(): void
    {
        if ($this->isDynamicStoreEnabled() === false) {
            Store::getInstance()->setCurrentLocale(static::LOCALE_EN_US);
        }
    }
}

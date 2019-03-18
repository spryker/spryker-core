<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\TaxProductConnector;

use Codeception\Actor;
use Orm\Zed\Tax\Persistence\SpyTaxRate;

/**
 * Inherited Methods
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
class TaxProductConnectorBusinessTester extends Actor
{
    use _generated\TaxProductConnectorBusinessTesterActions;

    /**
     * @param float $taxRate
     *
     * @return \Orm\Zed\Tax\Persistence\SpyTaxRate
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function haveTaxRate(float $taxRate): SpyTaxRate
    {


        $taxRateEntity = new SpyTaxRate();
        $taxRateEntity->setRate(13.04);
        $taxRateEntity->setFkCountry(60);
        $taxRateEntity->setName('Germany Standard test');
        $taxRateEntity->save();

        return $taxRateEntity;
    }
}

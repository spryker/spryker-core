<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\PriceProductVolume;

use Codeception\Actor;
use Generated\Shared\Transfer\MoneyValueTransfer;
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
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class PriceProductVolumeServiceTester extends Actor
{
    use _generated\PriceProductVolumeServiceTesterActions;

    /**
     * @var string
     */
    protected const DEFAULT_PRICE_DATA_JSON = '{"volume_prices":[{"quantity":1,"net_price":110,"gross_price":120},{"quantity":100,"net_price":80,"gross_price":100}]}';

    /**
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function createBasePriceProductTransfer(): PriceProductTransfer
    {
        $baseMoneyValueTransfer = (new MoneyValueTransfer())
            ->setPriceData(static::DEFAULT_PRICE_DATA_JSON);

        return (new PriceProductTransfer())
            ->setMoneyValue($baseMoneyValueTransfer);
    }

    /**
     * @param int $quantity
     * @param int|null $grossAmount
     * @param int|null $netAmount
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function createVolumePriceProductTransfer(
        int $quantity,
        ?int $grossAmount = null,
        ?int $netAmount = null
    ): PriceProductTransfer {
        $moneyValueTransfer = (new MoneyValueTransfer())
            ->setGrossAmount($grossAmount)
            ->setNetAmount($netAmount);

        return (new PriceProductTransfer())
            ->setVolumeQuantity($quantity)
            ->setMoneyValue($moneyValueTransfer);
    }
}

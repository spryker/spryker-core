<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductValidity;

use Codeception\Actor;
use DateTime;
use Orm\Zed\ProductValidity\Persistence\SpyProductValidity;

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
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductValidityBusinessTester extends Actor
{
    use _generated\ProductValidityBusinessTesterActions;

    /**
     * @param int $idProduct
     * @param \DateTime|null $validFrom
     * @param \DateTime|null $validTo
     *
     * @return \Orm\Zed\ProductValidity\Persistence\SpyProductValidity
     */
    public function haveValidity(int $idProduct, ?DateTime $validFrom, ?DateTime $validTo): SpyProductValidity
    {
        $productValidityEntity = (new SpyProductValidity())
            ->setFkProduct($idProduct)
            ->setValidFrom($validFrom)
            ->setValidTo($validTo);

        $productValidityEntity->save();

        return $productValidityEntity;
    }
}

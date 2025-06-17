<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductValidity;

use Codeception\Actor;
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
 * @SuppressWarnings(\SprykerTest\Zed\Availability\PHPMD)
 */
class ProductValidityPersistenceTester extends Actor
{
    use _generated\ProductValidityPersistenceTesterActions;

    /**
     * @param array<string, mixed> $productValidityData
     *
     * @return \Orm\Zed\ProductValidity\Persistence\SpyProductValidity
     */
    public function haveProductValidity(array $productValidityData): SpyProductValidity
    {
        $productValidityEntity = new SpyProductValidity();
        $productValidityEntity->fromArray($productValidityData);

        if (isset($productValidityData['valid_from']) && is_string($productValidityData['valid_from'])) {
            $productValidityEntity->setValidFrom($productValidityData['valid_from']);
        }

        if (isset($productValidityData['valid_to']) && is_string($productValidityData['valid_to'])) {
            $productValidityEntity->setValidTo($productValidityData['valid_to']);
        }

        $productValidityEntity->save();

        return $productValidityEntity;
    }
}

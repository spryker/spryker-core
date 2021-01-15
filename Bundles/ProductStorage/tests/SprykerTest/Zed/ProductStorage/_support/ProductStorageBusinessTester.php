<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductStorage;

use Codeception\Actor;
use Spryker\Zed\ProductStorage\Business\ProductStorageBusinessFactory;
use Spryker\Zed\ProductStorage\Business\ProductStorageFacade;

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
class ProductStorageBusinessTester extends Actor
{
    use _generated\ProductStorageBusinessTesterActions;

    /**
     * @param bool $enableSingleValueAttributePermutation
     *
     * @return \Spryker\Zed\ProductStorage\Business\ProductStorageFacade
     */
    public function getProductStorageFacade(bool $enableSingleValueAttributePermutation = true): ProductStorageFacade
    {
        $mockConfig = $this->mockConfigMethod('isPermutationForSingleValueProductAttributesEnabled', function () use ($enableSingleValueAttributePermutation) {
            return $enableSingleValueAttributePermutation;
        });

        $productStorageBusinessFactory = new ProductStorageBusinessFactory();
        $productStorageBusinessFactory->setConfig($mockConfig);

        $productStorageFacade = new ProductStorageFacade();
        $productStorageFacade->setFactory($productStorageBusinessFactory);

        return $productStorageFacade;
    }
}

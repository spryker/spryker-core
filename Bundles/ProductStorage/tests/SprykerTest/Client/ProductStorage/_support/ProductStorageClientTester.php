<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductStorage;

use Codeception\Actor;
use Generated\Shared\Transfer\AttributeMapStorageTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ProductStorage\ProductStorageClientInterface;

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
class ProductStorageClientTester extends Actor
{
    use _generated\ProductStorageClientTesterActions;

    protected const TEST_PRODUCT_CONCRETE_ID = 777;

    /**
     * @return \Spryker\Client\ProductStorage\ProductStorageClientInterface
     */
    public function getProductStorageClient(): ProductStorageClientInterface
    {
        return $this->getLocator()
            ->productStorage()
            ->client();
    }

    /**
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function createProductViewTransfer(): ProductViewTransfer
    {
        return (new ProductViewTransfer())
            ->setAttributeMap(
                (new AttributeMapStorageTransfer())->setProductConcreteIds([static::TEST_PRODUCT_CONCRETE_ID])
            );
    }
}

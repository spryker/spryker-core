<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundleStorage;

use Codeception\Actor;
use Generated\Shared\Transfer\ProductBundleStorageTransfer;
use Orm\Zed\ProductBundleStorage\Persistence\SpyProductBundleStorageQuery;

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
class ProductBundleStorageCommunicationTester extends Actor
{
    use _generated\ProductBundleStorageCommunicationTesterActions;

    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductBundleStorageTransfer|null
     */
    public function findProductBundleStorageByFkProduct(int $idProductConcrete): ?ProductBundleStorageTransfer
    {
        $productBundleStorageEntity = $this->getProductBundleStorageQuery()->findOneByFkProduct($idProductConcrete);

        if (!$productBundleStorageEntity) {
            return null;
        }

        return (new ProductBundleStorageTransfer())->fromArray($productBundleStorageEntity->getData());
    }

    /**
     * @return \Orm\Zed\ProductBundleStorage\Persistence\SpyProductBundleStorageQuery
     */
    protected function getProductBundleStorageQuery(): SpyProductBundleStorageQuery
    {
        return SpyProductBundleStorageQuery::create();
    }
}

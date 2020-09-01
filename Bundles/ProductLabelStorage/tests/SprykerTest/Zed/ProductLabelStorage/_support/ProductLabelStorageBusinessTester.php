<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductLabelStorage;

use Codeception\Actor;
use Orm\Zed\ProductLabelStorage\Persistence\Map\SpyProductAbstractLabelStorageTableMap;
use Orm\Zed\ProductLabelStorage\Persistence\SpyProductAbstractLabelStorageQuery;
use Orm\Zed\ProductLabelStorage\Persistence\SpyProductLabelDictionaryStorageQuery;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;

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
 * @method \Spryker\Zed\ProductLabelStorage\Business\ProductLabelStorageFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductLabelStorageBusinessTester extends Actor
{
    use _generated\ProductLabelStorageBusinessTesterActions;

    /**
     * @param int $idProductAbstract
     *
     * @return bool
     */
    public function isProductAbstractLabelStorageRecordExists(int $idProductAbstract): bool
    {
        return $this->createProductAbstractLabelStorageQuery()
            ->filterByFkProductAbstract($idProductAbstract)
            ->exists();
    }

    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function getProductAbstractLabelStorageIdsByIdProductAbstract(int $idProductAbstract): array
    {
        return $this->createProductAbstractLabelStorageQuery()
            ->filterByFkProductAbstract($idProductAbstract)
            ->select([SpyProductAbstractLabelStorageTableMap::COL_ID_PRODUCT_ABSTRACT_LABEL_STORAGE])
            ->find()
            ->getData();
    }

    /**
     * @return \Orm\Zed\ProductLabelStorage\Persistence\SpyProductLabelDictionaryStorageQuery
     */
    public function createProductLabelDictionaryStorageQuery(): SpyProductLabelDictionaryStorageQuery
    {
        return SpyProductLabelDictionaryStorageQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductLabelStorage\Persistence\SpyProductAbstractLabelStorageQuery
     */
    public function createProductAbstractLabelStorageQuery(): SpyProductAbstractLabelStorageQuery
    {
        return SpyProductAbstractLabelStorageQuery::create();
    }

    /**
     * @return \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    public function getLocaleFacade(): LocaleFacadeInterface
    {
        return $this->getLocator()->locale()->facade();
    }
}

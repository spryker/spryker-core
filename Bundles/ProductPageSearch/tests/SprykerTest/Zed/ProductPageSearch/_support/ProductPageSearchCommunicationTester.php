<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPageSearch;

use Codeception\Actor;
use Generated\Shared\Transfer\ProductPageSearchTransfer;
use Orm\Zed\ProductPageSearch\Persistence\Base\SpyProductAbstractPageSearch;
use Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearchQuery;

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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(\SprykerTest\Zed\ProductPageSearch\PHPMD)
 */
class ProductPageSearchCommunicationTester extends Actor
{
    use _generated\ProductPageSearchCommunicationTesterActions;

    /**
     * @param int $idProductAbstract
     * @param string $storeName
     *
     * @return \Orm\Zed\ProductOfferAvailabilityStorage\Persistence\SpyProductOfferAvailabilityStorage|null
     */
    protected function findProductAbstractPageSearch(int $idProductAbstract, string $storeName): ?SpyProductAbstractPageSearch
    {
        return $this->getProductAbstractPageSearchPropelQuery()
            ->filterByStore($storeName)
            ->findOneByFkProductAbstract($idProductAbstract);
    }

    /**
     * @param int $idProductAbstract
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ProductPageSearchTransfer|null
     */
    public function findProductPageSearchTransfer(int $idProductAbstract, string $storeName): ?ProductPageSearchTransfer
    {
        $productAbstractPageSearchEntity = $this->findProductAbstractPageSearch($idProductAbstract, $storeName);

        if (!$productAbstractPageSearchEntity) {
            return null;
        }

        $decodedStructuredData = $this->getLocator()->utilEncoding()->service()->decodeJson($productAbstractPageSearchEntity->getStructuredData(), true);

        return (new ProductPageSearchTransfer())->fromArray($decodedStructuredData);
    }

    /**
     * @return \Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearchQuery
     */
    protected function getProductAbstractPageSearchPropelQuery(): SpyProductAbstractPageSearchQuery
    {
        return SpyProductAbstractPageSearchQuery::create();
    }
}

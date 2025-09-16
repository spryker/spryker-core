<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPageSearch;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\Transfer\ProductPageSearchTransfer;
use Orm\Zed\ProductPageSearch\Persistence\Base\SpyProductAbstractPageSearch;
use Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearchQuery;
use Orm\Zed\ProductPageSearch\Persistence\SpyProductConcretePageSearchQuery;
use ReflectionClass;
use Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacade;
use Spryker\Zed\ProductPageSearch\Business\Publisher\ProductAbstractPagePublisher;
use Spryker\Zed\ProductPageSearch\Business\Publisher\ProductConcretePageSearchPublisher;

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
     * @return (\object&\PHPUnit\Framework\MockObject\MockObject)|(\Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacade&\object&\PHPUnit\Framework\MockObject\MockObject)|(\Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacade&\object&\PHPUnit\Framework\MockObject\MockObject&\object&\PHPUnit\Framework\MockObject\MockObject)
     */
    public function mockProductPageSearchFacade(): ProductPageSearchFacade
    {
        return Stub::makeEmpty(ProductPageSearchFacade::class);
    }

    /**
     * @return void
     */
    public function cleanUpProcessedAbstractProductIds()
    {
        $refClass = new ReflectionClass(ProductAbstractPagePublisher::class);
        $property = $refClass->getProperty('publishedProductAbstractIds');
        $property->setAccessible(true);
        $property->setValue([]);

        $property = $refClass->getProperty('unpublishedProductAbstractIds');
        $property->setAccessible(true);
        $property->setValue([]);
    }

    /**
     * @return void
     */
    public function cleanUpProcessedConcreteProductIds()
    {
        $refClass = new ReflectionClass(ProductConcretePageSearchPublisher::class);
        $property = $refClass->getProperty('publishedProductConcreteIds');
        $property->setAccessible(true);
        $property->setValue([]);

        $property = $refClass->getProperty('unpublishedProductConcreteIds');
        $property->setAccessible(true);
        $property->setValue([]);
    }

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

    /**
     * @param int $isAbstractProduct
     *
     * @return int|null
     */
    public function getProductAbstractPageSearchEntityTimestamp(int $isAbstractProduct): ?int
    {
        $spyProductAbstractPageSearchEntity = SpyProductAbstractPageSearchQuery::create()->findOneByFkProductAbstract($isAbstractProduct);

        return $spyProductAbstractPageSearchEntity ? $spyProductAbstractPageSearchEntity->getUpdatedAt()->getTimestamp() : null;
    }

    /**
     * @param int $isProductConcrete
     *
     * @return int|null
     */
    public function getProductConcreteStorageEntityTimestamp(int $isProductConcrete): ?int
    {
        $spyProductConcretePageSearchEntity = SpyProductConcretePageSearchQuery::create()->findOneByFkProduct($isProductConcrete);

        return $spyProductConcretePageSearchEntity ? $spyProductConcretePageSearchEntity->getUpdatedAt()->getTimestamp() : null;
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Zed\ProductOfferServicePoint;

use Codeception\Actor;
use Generated\Shared\Transfer\ProductOfferServiceTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ServiceTransfer;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Orm\Zed\ProductOfferServicePoint\Persistence\SpyProductOfferServiceQuery;
use Orm\Zed\ServicePoint\Persistence\SpyServicePointQuery;
use Orm\Zed\ServicePoint\Persistence\SpyServiceQuery;
use Orm\Zed\ServicePoint\Persistence\SpyServiceTypeQuery;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 * @method \Spryker\Zed\ProductOfferServicePoint\Business\ProductOfferServicePointFacadeInterface getFacade(?string $moduleName = null)
 *
 * @SuppressWarnings(\SprykerTest\Zed\ProductOfferServicePoint\PHPMD)
 */
class ProductOfferServicePointBusinessTester extends Actor
{
    use _generated\ProductOfferServicePointBusinessTesterActions;

    /**
     * @return void
     */
    public function ensureProductOfferServiceTableAndRelationsAreEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getProductOfferServiceQuery());
        $this->ensureDatabaseTableIsEmpty($this->getProductOfferQuery());
        $this->ensureDatabaseTableIsEmpty($this->getServiceQuery());
        $this->ensureDatabaseTableIsEmpty($this->getServicePointQuery());
        $this->ensureDatabaseTableIsEmpty($this->getServiceTypeQuery());
    }

    /**
     * @param int $count
     *
     * @return list<\Generated\Shared\Transfer\ProductOfferServiceTransfer>
     */
    public function createProductOfferServiceTransfers(int $count): array
    {
        $productOfferServiceTransfers = [];

        for ($i = 0; $i < $count; $i++) {
            $serviceTransfer = $this->haveService();
            $productOfferTransfer = $this->haveProductOffer();

            $productOfferServiceTransfers[] = $this->haveProductOfferService([
                ProductOfferServiceTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOfferOrFail(),
                ProductOfferServiceTransfer::ID_SERVICE => $serviceTransfer->getIdServiceOrFail(),
            ]);
        }

        return $productOfferServiceTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     * @param \Generated\Shared\Transfer\ServiceTransfer $serviceTransfer
     *
     * @return bool
     */
    public function hasProductOfferService(ProductOfferTransfer $productOfferTransfer, ServiceTransfer $serviceTransfer): bool
    {
        return $this->getProductOfferServiceQuery()
            ->filterByFkProductOffer($productOfferTransfer->getIdProductOfferOrFail())
            ->filterByFkService($serviceTransfer->getIdServiceOrFail())
            ->exists();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return int
     */
    public function getNumberOfPersistedProductOfferServices(ProductOfferTransfer $productOfferTransfer): int
    {
        return $this->getProductOfferServiceQuery()
            ->filterByFkProductOffer($productOfferTransfer->getIdProductOfferOrFail())
            ->count();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServiceTransfer $productOfferServiceTransfer
     *
     * @return int|null
     */
    public function findIdProductOfferService(ProductOfferServiceTransfer $productOfferServiceTransfer): ?int
    {
        $productOfferServiceEntity = $this->getProductOfferServiceQuery()
            ->filterByFkProductOffer($productOfferServiceTransfer->getIdProductOfferOrFail())
            ->filterByFkService($productOfferServiceTransfer->getIdServiceOrFail())
            ->findOne();

        if (!$productOfferServiceEntity) {
            return null;
        }

        return $productOfferServiceEntity->getIdProductOfferService();
    }

    /**
     * @return \Orm\Zed\ProductOfferServicePoint\Persistence\SpyProductOfferServiceQuery
     */
    protected function getProductOfferServiceQuery(): SpyProductOfferServiceQuery
    {
        return SpyProductOfferServiceQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function getProductOfferQuery(): SpyProductOfferQuery
    {
        return SpyProductOfferQuery::create();
    }

    /**
     * @return \Orm\Zed\ServicePoint\Persistence\SpyServiceQuery
     */
    protected function getServiceQuery(): SpyServiceQuery
    {
        return SpyServiceQuery::create();
    }

    /**
     * @return \Orm\Zed\ServicePoint\Persistence\SpyServicePointQuery
     */
    protected function getServicePointQuery(): SpyServicePointQuery
    {
        return SpyServicePointQuery::create();
    }

    /**
     * @return \Orm\Zed\ServicePoint\Persistence\SpyServiceTypeQuery
     */
    protected function getServiceTypeQuery(): SpyServiceTypeQuery
    {
        return SpyServiceTypeQuery::create();
    }
}

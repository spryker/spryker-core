<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\ProductOfferServicePoint\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\ProductOfferServiceTransfer;
use Orm\Zed\ProductOfferServicePoint\Persistence\SpyProductOfferService;
use Orm\Zed\ProductOfferServicePoint\Persistence\SpyProductOfferServiceQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class ProductOfferServiceHelper extends Module
{
    use DataCleanupHelperTrait;

    /**
     * @param array<string, mixed> $seed
     *
     * @return \Generated\Shared\Transfer\ProductOfferServiceTransfer
     */
    public function haveProductOfferService(array $seed = []): ProductOfferServiceTransfer
    {
        $productOfferServiceTransfer = (new ProductOfferServiceTransfer())->fromArray($seed, true);

        $productOfferServiceEntity = (new SpyProductOfferService())
            ->setFkProductOffer($productOfferServiceTransfer->getIdProductOfferOrFail())
            ->setFkService($productOfferServiceTransfer->getIdServiceOrFail());

        $productOfferServiceEntity->save();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($productOfferServiceEntity): void {
            $this->deleteProductOfferService($productOfferServiceEntity->getIdProductOfferService());
        });

        return $productOfferServiceTransfer;
    }

    /**
     * @param int $idProductOfferService
     *
     * @return void
     */
    protected function deleteProductOfferService(int $idProductOfferService): void
    {
        $productOfferServiceEntity = $this->getProductOfferServiceQuery()->findOneByIdProductOfferService($idProductOfferService);

        if ($productOfferServiceEntity) {
            $productOfferServiceEntity->delete();
        }
    }

    /**
     * @return \Orm\Zed\ProductOfferServicePoint\Persistence\SpyProductOfferServiceQuery
     */
    protected function getProductOfferServiceQuery(): SpyProductOfferServiceQuery
    {
        return SpyProductOfferServiceQuery::create();
    }
}

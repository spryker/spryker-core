<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferValidity\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ProductOfferValidityBuilder;
use Generated\Shared\Transfer\ProductOfferValidityTransfer;
use Orm\Zed\ProductOfferValidity\Persistence\SpyProductOfferValidity;
use Orm\Zed\ProductOfferValidity\Persistence\SpyProductOfferValidityQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ProductOfferValidityHelper extends Module
{
    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\ProductOfferValidityTransfer
     */
    public function haveProductOfferValidity(array $seedData = []): ProductOfferValidityTransfer
    {
        $productOfferValidityTransfer = (new ProductOfferValidityBuilder($seedData))->build();

        $productOfferValidityEntity = new SpyProductOfferValidity();
        $productOfferValidityEntity->fromArray($productOfferValidityTransfer->toArray());
        $productOfferValidityEntity->setFkProductOffer($productOfferValidityTransfer->getIdProductOffer());
        $productOfferValidityEntity->save();

        return $productOfferValidityTransfer;
    }

    /**
     * @return \Orm\Zed\ProductOfferValidity\Persistence\SpyProductOfferValidityQuery
     */
    public function getProductOfferValidityPropelQuery(): SpyProductOfferValidityQuery
    {
        return SpyProductOfferValidityQuery::create();
    }
}

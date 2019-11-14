<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\PriceProductOffer\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\PriceProductOfferBuilder;
use Generated\Shared\Transfer\PriceProductOfferTransfer;
use Orm\Zed\PriceProductOffer\Persistence\SpyPriceProductOffer;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class PriceProductOfferHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\PriceProductOfferTransfer
     */
    public function havePriceProductOffer(array $seedData = []): PriceProductOfferTransfer
    {
        /** @var \Generated\Shared\Transfer\PriceProductOfferTransfer $priceProductOfferTransfer */
        $priceProductOfferTransfer = (new PriceProductOfferBuilder($seedData))->build();
        $priceProductOfferTransfer->setIdPriceProductOffer(null);

        $priceProductOfferEntity = new SpyPriceProductOffer();
        $priceProductOfferEntity->fromArray($priceProductOfferTransfer->toArray());
        $priceProductOfferEntity->save();

        $priceProductOfferTransfer = new PriceProductOfferTransfer();
        $priceProductOfferTransfer->fromArray($priceProductOfferEntity->toArray());

        $this->getDataCleanupHelper()->_addCleanup(
            function () use ($priceProductOfferEntity)
            {
                $priceProductOfferEntity->delete();
            }
        );
        return $priceProductOfferTransfer;
    }
}

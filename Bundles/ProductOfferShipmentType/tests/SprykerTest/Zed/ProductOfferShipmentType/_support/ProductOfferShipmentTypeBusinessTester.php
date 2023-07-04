<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferShipmentType;

use Codeception\Actor;
use Generated\Shared\Transfer\ProductOfferShipmentTypeTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Orm\Zed\ProductOfferShipmentType\Persistence\SpyProductOfferShipmentTypeQuery;
use Propel\Runtime\Collection\Collection;

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
 * @method \Spryker\Zed\ProductOfferShipmentType\Business\ProductOfferShipmentTypeFacadeInterface getFacade()
 *
 * @SuppressWarnings(\SprykerTest\Zed\ProductOfferShipmentType\PHPMD)
 */
class ProductOfferShipmentTypeBusinessTester extends Actor
{
    use _generated\ProductOfferShipmentTypeBusinessTesterActions;

    /**
     * @param int $idProductOffer
     *
     * @return \Propel\Runtime\Collection\Collection<\Orm\Zed\ProductOfferShipmentType\Persistence\SpyProductOfferShipmentType>
     */
    public function getProductOfferShipmentTypeEntitiesByIdProductOffer(int $idProductOffer): Collection
    {
        return $this->getProductOfferShipmentTypeQuery()
            ->filterByFkProductOffer($idProductOffer)
            ->find();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeTransfer $productOfferShipmentTypeTransfer
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     *
     * @return void
     */
    public function assertProductOfferShipmentTypeTransfer(
        ProductOfferShipmentTypeTransfer $productOfferShipmentTypeTransfer,
        ProductOfferTransfer $productOfferTransfer,
        ShipmentTypeTransfer $shipmentTypeTransfer
    ): void {
        $this->assertNotNull($productOfferShipmentTypeTransfer->getProductOffer());
        $this->assertSame(
            $productOfferTransfer->getIdProductOfferOrFail(),
            $productOfferShipmentTypeTransfer->getProductOffer()->getIdProductOffer(),
        );
        $this->assertCount(1, $productOfferShipmentTypeTransfer->getShipmentTypes());
        $this->assertSame(
            $shipmentTypeTransfer->getIdShipmentTypeOrFail(),
            $productOfferShipmentTypeTransfer->getShipmentTypes()->getIterator()->current()->getIdShipmentType(),
        );
    }

    /**
     * @return \Orm\Zed\ProductOfferShipmentType\Persistence\SpyProductOfferShipmentTypeQuery
     */
    public function getProductOfferShipmentTypeQuery(): SpyProductOfferShipmentTypeQuery
    {
        return SpyProductOfferShipmentTypeQuery::create();
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Merchant;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\DataBuilder\MerchantBuilder;
use Generated\Shared\DataBuilder\StoreRelationBuilder;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer;
use Generated\Shared\Transfer\PriceProductMerchantRelationshipValueTransfer;
use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;

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
 * @method \Spryker\Zed\Merchant\Business\MerchantFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class MerchantBusinessTester extends Actor
{
    use _generated\MerchantBusinessTesterActions;

    /**
     * @return void
     */
    public function truncateMerchantRelations(): void
    {
        $this->truncateTableRelations($this->getMerchantQuery());
    }

    /**
     * @param int|null $merchantId
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function createMerchantTransfer(?int $merchantId = null): MerchantTransfer
    {
        return (new MerchantBuilder())
            ->build()
            ->setIdMerchant($merchantId)
            ->setStoreRelation((new StoreRelationBuilder())->build());
    }

    /**
     * @param int $merchantId
     * @param int $price
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer
     */
    public function createPriceProductMerchantRelationshipStorageTransfer(int $merchantId, int $price): PriceProductMerchantRelationshipStorageTransfer
    {
        $ungroupedPrices = new ArrayObject();
        $priceProductMerchantRelationshipValueTransfer = (new PriceProductMerchantRelationshipValueTransfer())
            ->setGrossPrice($price)
            ->setNetPrice($price)
            ->setFkMerchant($merchantId);
        $ungroupedPrices->append($priceProductMerchantRelationshipValueTransfer);

        return (new PriceProductMerchantRelationshipStorageTransfer())
            ->setUngroupedPrices($ungroupedPrices);
    }

    /**
     * @return \Orm\Zed\Merchant\Persistence\SpyMerchantQuery
     */
    protected function getMerchantQuery(): SpyMerchantQuery
    {
        return SpyMerchantQuery::create();
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Merchant;

use Codeception\Actor;
use Generated\Shared\DataBuilder\MerchantAddressBuilder;
use Generated\Shared\DataBuilder\MerchantAddressCollectionBuilder;
use Generated\Shared\DataBuilder\MerchantBuilder;
use Generated\Shared\Transfer\MerchantAddressCollectionTransfer;
use Generated\Shared\Transfer\MerchantAddressTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 * @method \Spryker\Zed\Merchant\Business\MerchantFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class MerchantBusinessTester extends Actor
{
    use _generated\MerchantBusinessTesterActions;

   /**
    * Define custom actions here
    */

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
    public function createMerchantTransferWithAddressTransfer(?int $merchantId = null): MerchantTransfer
    {
        $merchantTransfer = (new MerchantBuilder())
            ->build()
            ->setIdMerchant($merchantId);

        $merchantTransfer->setAddressCollection($this->createMerchantAddressCollectionTransfer());

        return $merchantTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantAddressCollectionTransfer
     */
    public function createMerchantAddressCollectionTransfer(): MerchantAddressCollectionTransfer
    {
        $merchantAddressCollectionTransfer = (new MerchantAddressCollectionBuilder())->build();
        $merchantAddressCollectionTransfer->addAddress($this->createMerchantAddressTransfer());

        return $merchantAddressCollectionTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantAddressTransfer
     */
    public function createMerchantAddressTransfer(): MerchantAddressTransfer
    {
        return (new MerchantAddressBuilder())
            ->build();
    }

    /**
     * @return \Orm\Zed\Merchant\Persistence\SpyMerchantQuery
     */
    protected function getMerchantQuery(): SpyMerchantQuery
    {
        return SpyMerchantQuery::create();
    }
}

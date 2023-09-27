<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ServicePointsRestApi;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\StoreRelationBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestServicePointTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;

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
 * @method \Spryker\Zed\ServicePointsRestApi\Business\ServicePointsRestApiFacadeInterface getFacade()
 *
 * @SuppressWarnings(\SprykerTest\Zed\ServicePointsRestApi\PHPMD)
 */
class ServicePointsRestApiBusinessTester extends Actor
{
    use _generated\ServicePointsRestApiBusinessTesterActions;

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function createStoreRelationTransfer(StoreTransfer $storeTransfer): StoreRelationTransfer
    {
        return (new StoreRelationBuilder([
            StoreRelationTransfer::STORES => (new ArrayObject([[
                StoreTransfer::NAME => $storeTransfer->getName(),
                StoreTransfer::ID_STORE => $storeTransfer->getIdStore(),
            ]])),
        ]))->build();
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     * @param list<string> $itemSkus
     *
     * @return \Generated\Shared\Transfer\RestServicePointTransfer
     */
    public function createRestServicePointTransfer(
        ServicePointTransfer $servicePointTransfer,
        array $itemSkus
    ): RestServicePointTransfer {
        return (new RestServicePointTransfer())
            ->setIdServicePoint($servicePointTransfer->getUuid())
            ->setItems($itemSkus);
    }

    /**
     * @param list<string> $itemGroupKeys
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransferWithItems(array $itemGroupKeys, ?StoreTransfer $storeTransfer): QuoteTransfer
    {
        $itemTransfers = [];
        foreach ($itemGroupKeys as $itemGroupKey) {
            $itemTransfers[] = (new ItemBuilder([ItemTransfer::GROUP_KEY => $itemGroupKey]))->build();
        }

        return (new QuoteTransfer())->setStore($storeTransfer)->setItems((new ArrayObject($itemTransfers)));
    }

    /**
     * @param bool $isActive
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointTransfer
     */
    public function createServicePoint(bool $isActive, ?StoreTransfer $storeTransfer): ServicePointTransfer
    {
        $servicePointData = [
            ServicePointTransfer::IS_ACTIVE => $isActive,
        ];

        if ($storeTransfer !== null) {
            $servicePointData[ServicePointTransfer::STORE_RELATION] = $this->createStoreRelationTransfer($storeTransfer);
        }

        return $this->haveServicePoint($servicePointData);
    }

    /**
     * @param list<\Generated\Shared\Transfer\RestServicePointTransfer> $restServicePointTransfers
     *
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function createRestCheckoutRequestAttributesTransfer(
        array $restServicePointTransfers
    ): RestCheckoutRequestAttributesTransfer {
        return (new RestCheckoutRequestAttributesTransfer())
            ->setServicePoints(new ArrayObject($restServicePointTransfers));
    }
}

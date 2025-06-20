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
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer;
use Generated\Shared\Transfer\PriceProductMerchantRelationshipValueTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;
use PHPUnit\Framework\Constraint\Callback;
use PHPUnit\Framework\MockObject\Rule\InvokedAtLeastOnce as InvokedAtLeastOnceMatcher;
use PHPUnit\Framework\MockObject\Rule\InvokedCount as InvokedCountMatcher;
use Spryker\Zed\Event\Business\EventFacadeInterface;
use Spryker\Zed\Merchant\Dependency\MerchantEvents;
use Spryker\Zed\Store\Business\StoreFacadeInterface;

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
 * @SuppressWarnings(\SprykerTest\Zed\Merchant\PHPMD)
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

    /**
     * @param int $numberOfInvokations
     *
     * @return \PHPUnit\Framework\MockObject\Rule\InvokedCount
     */
    protected function exactly(int $numberOfInvokations): InvokedCountMatcher
    {
        return new InvokedCountMatcher($numberOfInvokations);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\Rule\InvokedAtLeastOnce
     */
    public static function atLeastOnce(): InvokedAtLeastOnceMatcher
    {
        return new InvokedAtLeastOnceMatcher();
    }

    /**
     * @param callable $callback
     *
     * @return \PHPUnit\Framework\Constraint\Callback
     */
    protected static function callback(callable $callback): Callback
    {
        return new Callback($callback);
    }

    /**
     * @return \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    public function getStoreFacade(): StoreFacadeInterface
    {
        return $this->getLocator()->store()->facade();
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function haveMerchantWithStore(): MerchantTransfer
    {
        $storeRelationTransfer = $this->getStoreRelationTransfer();

        $merchantTransfer = $this->haveMerchant([MerchantTransfer::STORE_RELATION => $storeRelationTransfer->toArray()]);

        return $this->getFacade()->findOne((new MerchantCriteriaTransfer())->setIdMerchant($merchantTransfer->getIdMerchant()));
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function getMerchantTransferWithStore(): MerchantTransfer
    {
        $storeRelationTransfer = $this->getStoreRelationTransfer();

        return $this->getMerchantTransfer([MerchantTransfer::STORE_RELATION => $storeRelationTransfer->toArray()]);
    }

    /**
     * @param (\object&\PHPUnit\Framework\MockObject\MockObject)|\Spryker\Zed\Event\Business\EventFacadeInterface|object $eventFacade
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return void
     */
    public function assertTriggerMerchantExportEventsSuccessfully(EventFacadeInterface $eventFacade, MerchantTransfer $merchantTransfer): void
    {
        $merchantStore = $merchantTransfer->getStoreRelation()->getStores()->offsetGet(0);
        $merchantCriteriaTransfer = (new MerchantCriteriaTransfer())->setStore($merchantStore);
        $merchantCollectionTransfer = $this->getFacade()->get($merchantCriteriaTransfer);

        $eventFacade->expects($this->atLeastOnce())
            ->method('triggerBulk')
            ->with(MerchantEvents::MERCHANT_EXPORTED, $this->callback(
                function ($transfers) use ($merchantCollectionTransfer) {
                    $this->assertNotEmpty($transfers);
                    $this->assertInstanceOf(EventEntityTransfer::class, $transfers[0]);
                    $this->assertCount($merchantCollectionTransfer->getMerchants()->count(), $transfers);

                    return true;
                },
            ));
    }

    /**
     * @param \Spryker\Zed\Event\Business\EventFacadeInterface $eventFacade
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return void
     */
    public function assertTriggerCreatedAndPublishEvent(EventFacadeInterface $eventFacade, MerchantTransfer $merchantTransfer): void
    {
        $eventFacade
            ->expects($this->exactly(2))
            ->method('trigger')
            ->willReturnCallback(function ($event, $eventEntityTransfer) use ($merchantTransfer, &$invocationCount) {
                $invocationCount++;

                $eventMap = [
                    1 => MerchantEvents::MERCHANT_CREATED,
                    2 => MerchantEvents::MERCHANT_PUBLISH,
                ];

                if (isset($eventMap[$invocationCount])) {
                    $this->assertEquals($eventMap[$invocationCount], $event);
                    $this->assertNotEmpty($eventEntityTransfer);
                    $this->assertEquals($eventEntityTransfer->getId(), $merchantTransfer->getIdMerchant());
                }

                return true;
            });
    }

    /**
     * @param \Spryker\Zed\Event\Business\EventFacadeInterface $eventFacade
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return void
     */
    public function assertTriggerUpdatedAndPublishEvent(EventFacadeInterface $eventFacade, MerchantTransfer $merchantTransfer): void
    {
        $eventFacade
            ->expects($this->atLeastOnce())
            ->method('trigger')
            ->willReturnCallback(function ($event, $eventEntityTransfer) use ($merchantTransfer, &$invocationCount) {
                $invocationCount++;

                if ($invocationCount === 1) {
                    $this->assertEquals(MerchantEvents::MERCHANT_UPDATED, $event);
                    $this->assertNotEmpty($eventEntityTransfer);
                    $this->assertEquals($eventEntityTransfer->getId(), $merchantTransfer->getIdMerchant());
                } elseif ($invocationCount === 2) {
                    $this->assertEquals(MerchantEvents::MERCHANT_PUBLISH, $event);
                    $this->assertNotEmpty($eventEntityTransfer);
                    $this->assertEquals($eventEntityTransfer->getId(), $merchantTransfer->getIdMerchant());
                }

                return true;
            });
    }

    /**
     * @param \Spryker\Zed\Event\Business\EventFacadeInterface $eventFacade
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return void
     */
    public function assertTriggerOnlyPublishEvent(EventFacadeInterface $eventFacade, MerchantTransfer $merchantTransfer): void
    {
        $eventFacade
            ->expects($this->exactly(1))
            ->method('trigger')
            ->with(MerchantEvents::MERCHANT_PUBLISH, $this->callback(function ($eventEntityTransfer) use ($merchantTransfer) {
                $this->assertNotEmpty($eventEntityTransfer);
                $this->assertEquals($eventEntityTransfer->getId(), $merchantTransfer->getIdMerchant());

                return true;
            }));
    }

    /**
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function getStoreRelationTransfer(): StoreRelationTransfer
    {
        $storeTransfer = $this->haveStore([StoreTransfer::NAME => 'DE']);
        $storeRelationTransfer = (new StoreRelationBuilder())->seed([
            StoreRelationTransfer::ID_STORES => [$storeTransfer->getIdStore()],
        ])->build();

        return $storeRelationTransfer;
    }

    /**
     * @return array
     */
    public function getStoreReferences(): array
    {
        $storeReferences = [];

        foreach ($this->getStoreFacade()->getAllStores() as $storeTransfer) {
            if ($storeTransfer->getStoreReference()) {
                $storeReferences[] = $storeTransfer->getStoreReference();
            }
        }

        return $storeReferences;
    }
}

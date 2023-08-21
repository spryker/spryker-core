<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Merchant\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantExportCriteriaTransfer;
use Generated\Shared\Transfer\MerchantPublisherConfigTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Event\Business\EventFacade;
use Spryker\Zed\Merchant\Business\Exception\MerchantPublisherEventNameMismatchException;
use Spryker\Zed\Merchant\Dependency\Facade\MerchantToEventFacadeBridge;
use SprykerTest\Zed\MessageBroker\Helper\InMemoryMessageBrokerHelperTrait;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Merchant
 * @group Business
 * @group Facade
 * @group MerchantFacadeTest
 * Add your own group annotations below this line
 */
class MerchantFacadeTest extends Unit
{
    use InMemoryMessageBrokerHelperTrait;

    /**
     * @var \SprykerTest\Zed\Merchant\MerchantBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\Merchant\Dependency\Facade\MerchantToMessageBrokerFacadeInterface
     */
    protected $messageBrokerFacade;

    /**
     * @var \Spryker\Zed\Merchant\Dependency\Facade\MerchantToEventFacadeInterface
     */
    protected $eventFacade;

    /**
     * @var string
     */
    protected const DE_STORE_REFERENCE = 'dev-DE';

    /**
     * @var string
     */
    protected const DE_STORE_NAME = 'DE';

    /**
     * @var string
     */
    protected const AT_STORE_REFERENCE = 'dev-AT';

    /**
     * @var string
     */
    protected const AT_STORE_NAME = 'AT';

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->eventFacade = $this->createMock(EventFacade::class);

        $this->tester->mockFactoryMethod('getEventFacade', new MerchantToEventFacadeBridge($this->eventFacade));

        $this->tester->setStoreReferenceData([
            static::DE_STORE_NAME => static::DE_STORE_REFERENCE,
            static::AT_STORE_NAME => static::AT_STORE_REFERENCE,
        ]);
    }

    /**
     * @return void
     */
    public function testTriggerMerchantExportEventsSuccessfully(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchantWithStore();
        $merchantStore = $merchantTransfer->getStoreRelation()->getStores()->offsetGet(0);
        $merchantExportCriteriaTransfer = (new MerchantExportCriteriaTransfer())->setStoreReference($merchantStore->getStoreReference());

        // Assert
        $this->tester->assertTriggerMerchantExportEventsSuccessfully($this->eventFacade, $merchantTransfer);

        // Act
        $this->tester->getFacade()->triggerMerchantExportEvents($merchantExportCriteriaTransfer);
    }

    /**
     * @return void
     */
    public function testTriggerExportMerchantsSuccessfully(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchantWithStore();
        $merchantExportCriteriaTransfer = (new MerchantExportCriteriaTransfer());

        // Assert
        $this->tester->assertTriggerMerchantExportEventsSuccessfully($this->eventFacade, $merchantTransfer);

        // Act
        $this->tester->getFacade()->triggerMerchantExportEvents($merchantExportCriteriaTransfer);
    }

    /**
     * @return void
     */
    public function testMerchantCreatedWithStoreTriggersCreatedAndPublishEvents(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->getMerchantTransferWithStore();

        // Assert
        $this->tester->assertTriggerCreatedAndPublishEvent($this->eventFacade, $merchantTransfer);

        // Act
        $this->tester->getFacade()->createMerchant($merchantTransfer);
    }

    /**
     * @return void
     */
    public function testMerchantUpdateWithStoreTriggerUpdatedAndPublishEvent(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchantWithStore();
        $merchantTransfer->setName('updated-name');

        // Assert
        $this->tester->assertTriggerUpdatedAndPublishEvent($this->eventFacade, $merchantTransfer);

        // Act
        $this->tester->getFacade()->updateMerchant($merchantTransfer);
    }

    /**
     * @return void
     */
    public function testMerchantUpdateWithNewAssignedStoreTriggerUpdatedAndPublishEvent(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchantWithStore();

        $assignedStoreIds = array_map(function (StoreTransfer $store) {
            return $store->getIdStore();
        }, $merchantTransfer->getStoreRelation()->getStores()->getArrayCopy());

        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'NEW-STORE']);

        $assignedStoreIds[] = $storeTransfer->getIdStore();

        $merchantTransfer->getStoreRelation()->setIdStores($assignedStoreIds);

        // Assert
        $this->tester->assertTriggerUpdatedAndPublishEvent($this->eventFacade, $merchantTransfer);

        // Act
        $this->tester->getFacade()->updateMerchant($merchantTransfer);
    }

    /**
     * @return void
     */
    public function testMerchantUpdateRemovesAssignedStoreTriggerCreatedAndPublishEvent(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchantWithStore();

        $merchantTransfer->getStoreRelation()->setIdStores([]);

        // Assert
        $this->tester->assertTriggerUpdatedAndPublishEvent($this->eventFacade, $merchantTransfer);

        // Act
        $this->tester->getFacade()->updateMerchant($merchantTransfer);
    }

    /**
     * @return void
     */
    public function testMerchantsPublishedToMessageBrokerExpectsExceptionWhenEventIsNotAllowed(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchantWithStore();

        $merchantPublisherConfigTransfer = (new MerchantPublisherConfigTransfer())
            ->setMerchantIds([$merchantTransfer->getIdMerchant()])
            ->setEventName('unknown');

        // Assert
        $this->expectException(MerchantPublisherEventNameMismatchException::class);

        // Act
        $this->tester->getFacade()->emitPublishMerchantToMessageBroker($merchantPublisherConfigTransfer);
    }
}

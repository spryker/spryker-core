<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Publisher\Helper;

use Codeception\TestInterface;
use Exception;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\EventTriggerResponseTransfer;
use Orm\Zed\EventBehavior\Persistence\Map\SpyEventBehaviorEntityChangeTableMap;
use Spryker\Zed\Event\Business\EventFacadeInterface;
use Spryker\Zed\Event\Dependency\Client\EventToQueueBridge;
use Spryker\Zed\EventBehavior\Business\EventBehaviorFacadeInterface;
use Spryker\Zed\Publisher\Business\Collator\PublisherEventCollator;
use SprykerTest\Client\Queue\Helper\QueueHelper;
use SprykerTest\Client\Queue\Helper\QueueHelperTrait;
use SprykerTest\Shared\Testify\Helper\AbstractHelper;
use SprykerTest\Shared\Testify\Helper\StaticVariablesHelper;
use SprykerTest\Zed\Event\Helper\EventHelper;
use SprykerTest\Zed\EventBehavior\Helper\EventBehaviorHelper;
use SprykerTest\Zed\EventBehavior\Helper\EventBehaviorHelperTrait;
use SprykerTest\Zed\Testify\Helper\Business\BusinessHelperTrait;

class PublishAndSynchronizeHelper extends AbstractHelper
{
    use QueueHelperTrait;
    use EventBehaviorHelperTrait;
    use BusinessHelperTrait;
    use StaticVariablesHelper;

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        $this->cleanupStaticCache(PublisherEventCollator::class, 'eventCollectionBuffer');
        $this->validateHelpersAreEnabled();
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _after(TestInterface $test): void
    {
        $this->resetStaticCaches();
    }

    /**
     * @throws \Exception
     *
     * @return void
     */
    protected function validateHelpersAreEnabled(): void
    {
        $helpers = $this->getModules();

        $requiredHelpers = [
            EventHelper::class,
            QueueHelper::class,
            EventBehaviorHelper::class,
        ];

        $missingHelperMessages = [];

        foreach ($requiredHelpers as $requiredHelper) {
            if (!isset($helpers['\\' . $requiredHelper])) {
                $missingHelperMessages[] = sprintf('You need to add "\%s" to your enabled modules in the codeception.yml.', $requiredHelper);
            }
        }

        if (count($missingHelperMessages) > 0) {
            throw new Exception(implode("\n", $missingHelperMessages));
        }
    }

    /**
     * Helper method to:
     * - Assert at least 1 entry for the given `$eventName` exists in the `SpyEventBehaviorEntityChangeTableMap::TABLE_NAME` database table.
     * - Trigger runtime events to load the data from the `SpyEventBehaviorEntityChangeTableMap::TABLE_NAME` database table
     *   and add entries to the `$expectedPublishQueueName` queue.
     * - Assert that at least 1 message exists in the `$expectedPublishQueueName` queue.
     * - Assert that messages are consumed from the `$expectedPublishQueueName` and the queue is in a healthy state.
     *
     * Healthy state means that all messages which are received from the queue are either acknowledged, rejected or errored.
     *
     * @param string $eventName
     * @param string $expectedPublishQueueName
     *
     * @return void
     */
    public function assertEntityIsPublished(string $eventName, string $expectedPublishQueueName): void
    {
        $this->getEventBehaviorHelper()->assertAtLeastOneEventBehaviorEntityChangeEntryExistsForEvent($eventName);

        $eventTriggerResponseTransfer = $this->triggerRuntimeEvents();

        $this->addDebugInformation($eventTriggerResponseTransfer);

        $this->getQueueHelper()->assertQueueMessageCount($expectedPublishQueueName, 1);
        $this->getQueueHelper()->assertMessagesConsumedFromEventQueue($expectedPublishQueueName);
    }

    /**
     * @param \Generated\Shared\Transfer\EventTriggerResponseTransfer $eventTriggerResponseTransfer
     *
     * @return void
     */
    protected function addDebugInformation(EventTriggerResponseTransfer $eventTriggerResponseTransfer): void
    {
        codecept_debug($this->format(sprintf('EventBehavior table exists: ', $eventTriggerResponseTransfer->getEventBehaviorTableExistsOrFail() ? '<fg=green>true</>' : '<fg=red>false</>')));
        codecept_debug($this->format(sprintf('Triggering is active: ', $eventTriggerResponseTransfer->getIsEventTriggeringActiveOrFail() ? '<fg=green>true</>' : '<fg=red>false</>')));
        codecept_debug($this->format(sprintf('RequestID: <fg=yellow>%s</>', $eventTriggerResponseTransfer->getRequestIdOrFail())));
        codecept_debug($this->format(sprintf('Events: <fg=yellow>%s</>', $eventTriggerResponseTransfer->getEventCountOrFail())));
        codecept_debug($this->format(sprintf('Triggered rows: <fg=yellow>%s</>', $eventTriggerResponseTransfer->getTriggeredRowsOrFail())));
    }

    /**
     * Helper method to:
     * - Trigger manual event for passed `$ids` to let the assigned listeners for this event do it's job
     *   and add entries to the `$expectedPublishQueueName` queue.
     * - Assert that at least 1 message exists in the `$expectedPublishQueueName` queue.
     * - Assert that messages are consumed from the `$expectedPublishQueueName` and the queue is in a healthy state.
     *
     * Healthy state means that all messages which are received from the queue are either acknowledged, rejected or errored.
     *
     * @param string $eventName
     * @param array $ids
     * @param string $expectedPublishQueueName
     *
     * @return void
     */
    public function assertEntityCanBeManuallyPublished(string $eventName, array $ids, string $expectedPublishQueueName): void
    {
        $eventEntityTransferCollection = [];

        foreach ($ids as $id) {
            $eventEntityTransferCollection[] = (new EventEntityTransfer())->setId($id);
        }

        $this->triggerManualEvents($eventName, $eventEntityTransferCollection);

        $this->getQueueHelper()->assertQueueMessageCount($expectedPublishQueueName, 1);
        $this->getQueueHelper()->assertMessagesConsumedFromEventQueue($expectedPublishQueueName);
    }

    /**
     * @return \Generated\Shared\Transfer\EventTriggerResponseTransfer
     */
    protected function triggerRuntimeEvents(): EventTriggerResponseTransfer
    {
        $eventTriggerResponseTransfer = $this->getEventBehaviorHelper()->triggerRuntimeEvents();

        codecept_debug($this->format(sprintf(
            'Triggered <fg=green>%s::triggerRuntimeEvents()</> to move data from the <fg=green>%s</> database table into the event queue.',
            EventBehaviorFacadeInterface::class,
            SpyEventBehaviorEntityChangeTableMap::TABLE_NAME,
        )));

        return $eventTriggerResponseTransfer;
    }

    /**
     * @param string $eventName
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransferCollection
     *
     * @return void
     */
    protected function triggerManualEvents(string $eventName, array $eventEntityTransferCollection): void
    {
        $this->getEventFacade()->triggerBulk($eventName, $eventEntityTransferCollection);

        codecept_debug($this->format(sprintf(
            'Triggered <fg=green>%s::triggerBulk()</> to manually add the required data into the event queue.',
            EventFacadeInterface::class,
        )));
    }

    /**
     * @return \Spryker\Zed\Event\Business\EventFacadeInterface
     */
    protected function getEventFacade(): EventFacadeInterface
    {
        // Set the in-memory queue to be used for further processing
        $eventToQueueBridge = new EventToQueueBridge($this->getQueueHelper()->getQueueClient());
        $this->getBusinessHelper()->mockFactoryMethod('getQueueClient', $eventToQueueBridge, 'Event');

        /** @var \Spryker\Zed\Event\Business\EventFacadeInterface $eventFacade */
        $eventFacade = $this->getBusinessHelper()->getFacade('Event');

        return $eventFacade;
    }

    /**
     * Helper method to:
     * - Assert that at least 1 message exists in the `$expectedSyncQueueName` queue.
     * - Assert that messages are consumed from the `$expectedSyncQueueName` and the queue is in a healthy state.
     *
     * Healthy state means that all messages which are received from the queue are either acknowledged, rejected or errored.
     *
     * @param string $expectedSyncQueueName
     *
     * @return void
     */
    public function assertEntityIsSynchronizedToStorage(string $expectedSyncQueueName): void
    {
        $this->getQueueHelper()->assertQueueMessageCount($expectedSyncQueueName, 1);
        $this->getQueueHelper()->assertMessagesConsumedFromQueueAndSyncedToStorage($expectedSyncQueueName);

        $this->getQueueHelper()->cleanupInMemoryQueue();
    }

    /**
     * Helper method to:
     * - Assert that at least 1 message exists in the `$expectedSyncQueueName` queue.
     * - Assert that messages are consumed from the `$expectedPublishQueueName` and the queue is in a healthy state.
     *
     * Healthy state means that all messages which are received from the queue are either acknowledged, rejected or errored.
     *
     * @param string $expectedSyncQueueName
     *
     * @return void
     */
    public function assertEntityIsUpdatedInStorage(string $expectedSyncQueueName): void
    {
        $this->getQueueHelper()->assertQueueMessageCount($expectedSyncQueueName, 1);
        $this->getQueueHelper()->assertMessagesConsumedFromQueueAndUpdatedInStorage($expectedSyncQueueName);

        $this->getQueueHelper()->cleanupInMemoryQueue();
    }

    /**
     * Helper method to:
     * - Assert that at least 1 message exists in the `$expectedSyncQueueName` queue.
     * - Assert that messages are consumed from the `$expectedPublishQueueName` and the queue is in a healthy state.
     *
     * Healthy state means that all messages which are received from the queue are either acknowledged, rejected or errored.
     *
     * @param string $expectedSyncQueueName
     *
     * @return void
     */
    public function assertEntityIsRemovedFromStorage(string $expectedSyncQueueName): void
    {
        $this->getQueueHelper()->assertQueueMessageCount($expectedSyncQueueName, 1);
        $this->getQueueHelper()->assertMessagesConsumedFromQueueAndRemovedFromStorage($expectedSyncQueueName);

        $this->getQueueHelper()->cleanupInMemoryQueue();
    }

    /**
     * Helper method to:
     * - Assert that at least 1 message exists in the `$expectedSyncQueueName` queue.
     * - Assert that messages are consumed from the `$expectedPublishQueueName` and the queue is in a healthy state.
     *
     * Healthy state means that all messages which are received from the queue are either acknowledged, rejected or errored.
     *
     * @param string $expectedSyncQueueName
     *
     * @return void
     */
    public function assertEntityIsSynchronizedToSearch(string $expectedSyncQueueName): void
    {
        $this->getQueueHelper()->assertQueueMessageCount($expectedSyncQueueName, 1);
        $this->getQueueHelper()->assertMessagesConsumedFromQueueAndSyncedToSearch($expectedSyncQueueName);

        $this->getQueueHelper()->cleanupInMemoryQueue();
    }

    /**
     * Helper method to:
     * - Assert that at least 1 message exists in the `$expectedSyncQueueName` queue.
     * - Assert that messages are consumed from the `$expectedPublishQueueName` and the queue is in a healthy state.
     *
     * Healthy state means that all messages which are received from the queue are either acknowledged, rejected or errored.
     *
     * @param string $expectedSyncQueueName
     *
     * @return void
     */
    public function assertEntityIsUpdatedInSearch(string $expectedSyncQueueName): void
    {
        $this->getQueueHelper()->assertQueueMessageCount($expectedSyncQueueName, 1);
        $this->getQueueHelper()->assertMessagesConsumedFromQueueAndUpdatedInSearch($expectedSyncQueueName);

        $this->getQueueHelper()->cleanupInMemoryQueue();
    }

    /**
     * Helper method to:
     * - Assert that at least 1 message exists in the `$expectedSyncQueueName` queue.
     * - Assert that messages are consumed from the `$expectedPublishQueueName` and the queue is in a healthy state.
     *
     * Healthy state means that all messages which are received from the queue are either acknowledged, rejected or errored.
     *
     * @param string $expectedSyncQueueName
     *
     * @return void
     */
    public function assertEntityIsRemovedFromSearch(string $expectedSyncQueueName): void
    {
        $this->getQueueHelper()->assertQueueMessageCount($expectedSyncQueueName, 1);
        $this->getQueueHelper()->assertMessagesConsumedFromQueueAndRemovedFromSearch($expectedSyncQueueName);

        $this->getQueueHelper()->cleanupInMemoryQueue();
    }
}

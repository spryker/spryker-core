<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\Publisher;

use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface;

class DataImporterPublisher implements DataImporterPublisherInterface
{
    /**
     * @var \Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface
     */
    protected $eventFacade;

    /**
     * @var array
     */
    protected static $importedEntityEvents = [];

    /**
     * @var array
     */
    protected static $triggeredEventIds = [];

    /**
     * @param \Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface $eventFacade
     */
    public function __construct(DataImportToEventFacadeInterface $eventFacade)
    {
        $this->eventFacade = $eventFacade;
    }

    /**
     * @param string $eventName
     * @param int $entityId
     *
     * @return void
     */
    public function addEvent($eventName, $entityId): void
    {
        if (isset(static::$triggeredEventIds[$eventName][$entityId])) {
            return;
        }

        static::$importedEntityEvents[$eventName][] = $entityId;
    }

    /**
     * @deprecated use addEvent() instead.
     *
     * @param array $events
     *
     * @return void
     */
    public static function addImportedEntityEvents(array $events): void
    {
        static::$importedEntityEvents = array_merge_recursive(static::$importedEntityEvents, $events);
    }

    /**
     * @param int $flushChunkSize
     *
     * @return void
     */
    public function triggerEvents($flushChunkSize = 1000000): void
    {
        $uniqueEvents = $this->getUniqueEvents();
        foreach ($uniqueEvents as $event => $ids) {
            $uniqueIds = array_unique($ids);
            $events = [];
            foreach ($uniqueIds as $id) {
                $events[] = (new EventEntityTransfer())->setId($id);
                static::$triggeredEventIds[$event][$id] = true;
            }
            $this->eventFacade->triggerBulk($event, $events);
        }

        static::$importedEntityEvents = [];

        if (count(static::$triggeredEventIds, COUNT_RECURSIVE) > $flushChunkSize) {
            static::$triggeredEventIds = [];
        }
    }

    /**
     * @return array
     */
    protected static function getUniqueImportedEntityEvents(): array
    {
        $uniqueArray = [];
        foreach (static::$importedEntityEvents as $event => $ids) {
            $uniqueArray[$event] = array_unique($ids);
        }

        return $uniqueArray;
    }

    /**
     * @return array
     */
    protected function getUniqueEvents(): array
    {
        $uniqueEvents = static::getUniqueImportedEntityEvents();
        foreach ($uniqueEvents as $eventName => $events) {
            foreach ($events as $entityKey => $entityId) {
                if (isset(static::$triggeredEventIds[$eventName][(int)$entityId])) {
                    unset($uniqueEvents[$eventName][$entityKey]);
                }
            }
        }

        return $uniqueEvents;
    }
}

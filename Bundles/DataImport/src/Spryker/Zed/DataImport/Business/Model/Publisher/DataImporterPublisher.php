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
     * @return void
     */
    public function triggerEvents(): void
    {
        foreach (static::getUniqueImportedEntityEvents() as $event => $ids) {
            $uniqueIds = array_unique($ids);
            $events = [];
            foreach ($uniqueIds as $id) {
                $events[] = (new EventEntityTransfer())->setId($id);
            }
            $this->eventFacade->triggerBulk($event, $events);
        }
        static::$importedEntityEvents = [];
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
}

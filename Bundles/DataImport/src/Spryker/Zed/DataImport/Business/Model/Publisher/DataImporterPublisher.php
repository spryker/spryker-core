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
     * @param array $events
     *
     * @return void
     */
    public static function addImportedEntityEvents(array $events): void
    {
        $mergedArray = array_merge_recursive(static::$importedEntityEvents, $events);

        static::$importedEntityEvents = static::getUniqueArray($mergedArray);
    }

    /**
     * @return void
     */
    public function triggerEvents(): void
    {
        foreach (static::$importedEntityEvents as $event => $ids) {
            $uniqueIds = array_unique($ids);
            foreach ($uniqueIds as $id) {
                $this->eventFacade->trigger($event, (new EventEntityTransfer())->setId($id));
            }
        }
    }

    /**
     * @param array $mergedArray
     *
     * @return array
     */
    protected static function getUniqueArray(array $mergedArray): array
    {
        $uniqueArray = [];
        foreach ($mergedArray as $event => $ids) {
            $uniqueArray[$event] = array_unique($ids);
        }

        return $uniqueArray;
    }
}

<?php
/**
 *
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerEngine\Shared\Lumberjack\Model;

use SprykerEngine\Shared\Lumberjack\Model\Writer;

abstract class AbstractEventJournal
{

    /**
     * @var DataCollectorInterface[]
     */
    private $dataCollectors;

    /**
     * @var WriterInterface[]
     */
    private $eventWriters;

    /**
     * @param DataCollectorInterface $dataCollector
     */
    public function addDataCollector(DataCollectorInterface $dataCollector)
    {
        $this->dataCollectors[get_class($dataCollector)] = $dataCollector;
    }

    /**
     * @param EventInterface $event
     */
    private function applyCollectors(EventInterface $event)
    {
        foreach ($this->dataCollectors as $collector) {
            $event->addFields($collector->getData());
        }
    }

    /**
     * @param EventInterface $event
     */
    public function saveEvent(EventInterface $event) {
        $this->applyCollectors($event);
        // foreach Writer ..
    }

    public function addEventWriter(WriterInterface $writer) {
        $this->eventWriters[get_class($writer)] = $writer;
    }
}
